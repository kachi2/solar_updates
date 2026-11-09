<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Exceptions\UserFacingException;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CheckoutRequest;
use App\Repositories\AddressRepository;
use App\Repositories\OrderRepository;
use App\Services\AddressService;
use App\Services\CheckoutService;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly AddressService $addressService,
        private readonly AddressRepository $addresses,
        private readonly OrderRepository $orders,
    ) {}

    /**
     * Checkout page (also snapshots the cart into order_items; see CheckoutService).
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($this->checkout->cartIsEmpty()) {
            return redirect()->route('index');
        }

        $this->checkout->snapshotCart($request->user());

        return view('users.products.checkout', [
            'shipping' => $this->addresses->defaultsFor($request->user()),
            'cart' => Cart::getContent(),
        ]);
    }

    /**
     * "Add address" pop-up on the checkout page.
     */
    public function storeAddress(AddressRequest $request): RedirectResponse
    {
        $this->addressService->add($request->user(), $request->shippingAttributes(), $request->boolean('default'));

        return back()->with('success', 'Address Updated Successfully');
    }

    /**
     * Place the order: card orders continue to the Flutterwave page,
     * pay-on-delivery orders are complete straight away.
     */
    public function store(Request $request): View|RedirectResponse
    {
        try {
            $order = $this->checkout->placeOrder($request->user(), $request->input('payment_method'));
        } catch (UserFacingException $e) {
            return $this->failWith($e);
        }

        if ($order->payment_method === PaymentMethod::Card->value) {
            return view('users.products.checkouts', [
                'address' => $this->addresses->defaultFor($request->user()),
                'cart' => Cart::getContent(),
                'order' => $order,
            ]);
        }

        session()->flash('success', 'Order was sent successfully');

        return view('users.products.orders', [
            'items' => $this->orders->items($order->order_No),
            'orders' => $order,
            'success' => 'Order was sent successfully',
        ]);
    }

    /**
     * Flutterwave callback.
     */
    public function verify(Request $request, string $transactionId): View|RedirectResponse
    {
        try {
            $order = $this->checkout->confirmCardPayment($request->user(), $transactionId);
        } catch (UserFacingException $e) {
            return $this->failWith($e);
        }

        session()->flash('success', 'Order was sent successfully');

        return view('users.products.completed', [
            'items' => $this->orders->items($order->order_No),
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
     * Save the address from addNew() and return to checkout.
     */
    public function add(CheckoutRequest $request): RedirectResponse
    {
        $this->addressService->add($request->user(), $request->validated());

        if ($this->checkout->cartIsEmpty()) {
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

        $user = $request->user();
        $address = $this->addresses->findForOrFail($user, $this->decryptId($checkout));
        $this->addressService->update($user, $address, $validated);

        return back()->with('message', 'Address Updated');
    }
}
