<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\AdminNotify;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Services\FlutterwaveService;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Checkout page. As in the old app, visiting it snapshots the cart into
     * order_items under a fresh order number that store() then uses.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cart = Cart::getContent();

        if ($cart->isEmpty()) {
            return redirect()->route('index');
        }

        $orderNo = random_int(100000000, 999999999);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_No' => $orderNo,
                'product_name' => $item->name,
                'qty' => $item->quantity,
                'price' => $item->price,
                'image' => $item->model?->image,
                'payable' => $item->quantity * $item->price,
                'user_id' => $request->user()->id,
            ]);
        }

        return view('users.products.checkout', [
            'shipping' => $request->user()->shippings()->where('is_default', 1)->get(),
            'cart' => $cart,
        ]);
    }

    /**
     * "Add address" modal on the checkout page.
     */
    public function storeAddress(AddressRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->boolean('default')) {
            $user->shippings()->where('is_default', 1)->update(['is_default' => 0]);
        }

        $user->shippings()->create($request->shippingAttributes() + [
            'is_default' => $request->boolean('default') ? 1 : 0,
        ]);

        return back()->with('success', 'Address Updated Successfully');
    }

    /**
     * Place the order. Card payments continue to the Flutterwave page;
     * other methods complete immediately (manual payment).
     */
    public function store(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $latestItem = OrderItem::where('user_id', $user->id)->latest()->first();

        if (! $latestItem) {
            return redirect()->route('carts.index');
        }

        if (Order::where('order_No', $latestItem->order_No)->exists()) {
            return redirect()->route('checkout.index')
                ->withErrors('Order number already exist, please check your order details and proceed with manual payment');
        }

        if (! $request->filled('payment_method')) {
            return redirect()->route('checkout.index')->withErrors('Please select a payment method below');
        }

        $shipping = $user->shippings()->where('is_default', 1)->first();

        if (! $shipping) {
            return redirect()->route('checkout.index')->withErrors('Please select a default Address');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'order_No' => $latestItem->order_No,
            'payment_method' => $request->input('payment_method'),
            'is_paid' => 0,
            'is_delivered' => 0,
            'amount' => Cart::getSubTotal(false),
            'shipping_id' => $shipping->id,
        ]);

        if ($request->input('payment_method') === 'card') {
            return view('users.products.checkouts', [
                'address' => $shipping,
                'cart' => Cart::getContent(),
                'flutterwavePublicKey' => config('services.flutterwave.public_key'),
            ]);
        }

        Cart::clear();
        AdminNotify::create(['message' => 'New customer order completed Order No: '.$order->order_No]);
        session()->flash('success', 'Order was sent successfully');

        return view('users.products.orders', [
            'items' => OrderItem::where('order_No', $order->order_No)->get(),
            'orders' => $order,
            'success' => 'Order was sent successfully',
        ]);
    }

    /**
     * Flutterwave callback: verify the transaction server-side before marking
     * the customer's latest unpaid order as paid.
     */
    public function verify(Request $request, string $transactionId, FlutterwaveService $flutterwave): View|RedirectResponse
    {
        $user = $request->user();
        $transaction = $flutterwave->verifyTransaction($transactionId);

        if (! $transaction) {
            return redirect()->route('checkout.index')->withErrors('Transaction not found, Please contact support');
        }

        if (Transaction::where('external_ref', $transaction['flw_ref'] ?? '')->exists()) {
            return redirect()->route('users.orders')->with('success', 'This payment has already been confirmed');
        }

        $order = Order::where('user_id', $user->id)->where('is_paid', 0)->latest()->first();

        if (! $order || ! $flutterwave->isSuccessfulPayment($transaction, (float) $order->amount)) {
            return redirect()->route('checkout.index')
                ->withErrors('Your payment was not successful. Something went wrong.');
        }

        DB::transaction(function () use ($user, $order, $transaction) {
            Transaction::create([
                'user_id' => $user->id,
                'payment_ref' => $transaction['tx_ref'],
                'type' => 'payment for',
                'payment_method' => 'card',
                'external_ref' => $transaction['flw_ref'],
                'amount' => $transaction['amount'],
                'order_No' => $order->order_No,
            ]);

            $order->update([
                'payment_ref' => $transaction['tx_ref'],
                'external_ref' => $transaction['flw_ref'],
                'is_paid' => 1,
                'amount' => $transaction['amount'],
            ]);

            AdminNotify::create(['message' => 'New customer order completed <br>Order No'.$order->order_No]);
        });

        Cart::clear();
        session()->flash('success', 'Order was sent successfully');

        return view('users.products.completed', [
            'items' => OrderItem::where('order_No', $order->order_No)->get(),
            'orders' => $order,
            'success' => 'Order completed successfully',
        ]);
    }

    /**
     * "Checkout with a new address" page.
     */
    public function addNew(Request $request): View
    {
        return view('users.products.edit', [
            'title' => 'Checkout',
            'user' => $request->user(),
            'carts' => Cart::getContent(),
        ]);
    }

    /**
     * Save the address from addNew(). The old app rendered a
     * `users.products.payment` view that never existed; this returns to checkout.
     */
    public function add(CheckoutRequest $request): RedirectResponse
    {
        $request->user()->shippings()->create($request->validated());

        if (Cart::getContent()->isEmpty()) {
            return redirect()->route('carts.index');
        }

        return redirect()->route('checkout.index')->with('success', 'Address saved');
    }

    /**
     * Update one of the customer's addresses.
     */
    public function update(Request $request, string $checkout): RedirectResponse
    {
        $validated = $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:30'],
            'receiver_email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->shippings()->findOrFail($this->decryptId($checkout))->update($validated);

        return back()->with('message', 'Address Updated');
    }
}
