<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Exceptions\UserFacingException;
use App\Models\Order;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\DB;

/**
 * Turns the cart into an order and confirms card payments.
 *
 * Flow (kept from the old app): visiting checkout snapshots the cart into
 * order_items under a fresh order number; placing the order creates the
 * `orders` row for that number; card orders are marked paid only after
 * Flutterwave confirms the payment server-side.
 */
class CheckoutService
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly AddressRepository $addresses,
        private readonly TransactionRepository $transactions,
        private readonly FlutterwaveService $flutterwave,
        private readonly NotificationService $notifications,
    ) {}

    public function cartIsEmpty(): bool
    {
        return Cart::getContent()->isEmpty();
    }

    public function cartTotal(): float
    {
        return (float) Cart::getSubTotal(false);
    }

    /**
     * Copy the current cart into order_items under a new order number.
     */
    public function snapshotCart(User $user): int
    {
        $orderNo = random_int(100000000, 999999999);

        foreach (Cart::getContent() as $item) {
            $this->orders->addItem([
                'order_No' => $orderNo,
                'product_name' => $item->name,
                'qty' => $item->quantity,
                'price' => $item->price,
                'image' => $item->model?->image,
                'payable' => $item->quantity * $item->price,
                'user_id' => $user->id,
            ]);
        }

        return $orderNo;
    }

    /**
     * Create the order for the customer's latest cart snapshot.
     *
     * @throws UserFacingException
     */
    public function placeOrder(User $user, ?string $paymentMethod): Order
    {
        $latestItem = $this->orders->latestItemForUser($user);

        if (! $latestItem) {
            throw new UserFacingException('Your cart is empty.', 'carts.index');
        }

        if ($this->orders->existsByNumber($latestItem->order_No)) {
            throw new UserFacingException('Order number already exist, please check your order details and proceed with manual payment', 'checkout.index');
        }

        $method = PaymentMethod::tryFrom((string) $paymentMethod);

        if (! $method) {
            throw new UserFacingException('Please select a payment method below', 'checkout.index');
        }

        $shipping = $this->addresses->defaultFor($user);

        if (! $shipping) {
            throw new UserFacingException('Please select a default Address', 'checkout.index');
        }

        $order = $this->orders->create([
            'user_id' => $user->id,
            'order_No' => $latestItem->order_No,
            'payment_method' => $method->value,
            'is_paid' => PaymentStatus::Pending->value,
            'is_delivered' => OrderStatus::Pending->value,
            'amount' => $this->cartTotal(),
            'shipping_id' => $shipping->id,
        ]);

        // Pay-on-delivery orders are complete now; card orders wait for Flutterwave.
        if ($method === PaymentMethod::CashOnDelivery) {
            Cart::clear();
            $this->notifications->newOrderPlaced($order->order_No);
        }

        return $order;
    }

    /**
     * Verify a Flutterwave transaction and mark the customer's latest unpaid order as paid.
     *
     * @throws UserFacingException
     */
    public function confirmCardPayment(User $user, string $transactionId): Order
    {
        $transaction = $this->flutterwave->verifyTransaction($transactionId);

        if (! $transaction) {
            throw new UserFacingException('Transaction not found, Please contact support', 'checkout.index');
        }

        if ($this->transactions->existsByExternalRef($transaction['flw_ref'] ?? null)) {
            throw new UserFacingException('This payment has already been confirmed', 'users.orders');
        }

        $order = $this->orders->latestUnpaidForUser($user);

        if (! $order || ! $this->flutterwave->isSuccessfulPayment($transaction, (float) $order->amount)) {
            throw new UserFacingException('Your payment was not successful. Something went wrong.', 'checkout.index');
        }

        DB::transaction(function () use ($user, $order, $transaction) {
            $this->transactions->create([
                'user_id' => $user->id,
                'payment_ref' => $transaction['tx_ref'],
                'type' => TransactionType::Payment->value,
                'payment_method' => PaymentMethod::Card->value,
                'external_ref' => $transaction['flw_ref'],
                'amount' => $transaction['amount'],
                'order_No' => $order->order_No,
            ]);

            $order->update([
                'payment_ref' => $transaction['tx_ref'],
                'external_ref' => $transaction['flw_ref'],
                'is_paid' => PaymentStatus::Paid->value,
                'amount' => $transaction['amount'],
            ]);

            $this->notifications->newOrderPlaced($order->order_No);
        });

        Cart::clear();

        return $order;
    }
}
