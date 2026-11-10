<?php

namespace App\Http\Controllers;

use App\Enums\Visibility;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly ProductRepository $products) {}

    public function index(): View
    {
        return view('users.products.carts', [
            'cart' => Cart::getContent(),
            'breadcrumb' => 'Shopping Cart',
        ]);
    }

    public function add(Request $request, string $id): JsonResponse
    {
        $product = $this->products->findOrFail($id);

        // Hidden products and products without a price (WhatsApp-only) can't be bought online.
        abort_if($product->visibility() === Visibility::Hidden, 404);
        abort_unless($product->hasPrice(), 422, 'This product has no price yet. Please ask for the price on WhatsApp.');

        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        Cart::add(
            $product->id,
            $product->name,
            $product->sale_price,
            $validated['qty'],
            ['image' => $product->image, 'weight' => 1],
            [],
            Product::class
        );

        $item = Cart::getContent()->get($product->id);

        return response()->json([
            'cartQuantity' => (int) Cart::getTotalQuantity(),
            'subtotal' => Cart::getSubTotal(false),
            'item' => [
                'name' => $item->name,
                'image' => $product->image,
                'price' => $item->price,
                'quantity' => (int) $item->quantity,
                'total' => $item->price * $item->quantity,
            ],
        ]);
    }

    /**
     * Change an item's quantity; the price comes from the product's
     * quantity/price variation when one matches.
     */
    public function update(Request $request, string $cart): RedirectResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
            'rowId' => ['required'],
        ]);

        $tierPrice = $this->products->variationPrice($this->decryptId($cart), (int) $validated['qty']);

        Cart::update($validated['rowId'], array_filter([
            'price' => $tierPrice,
            'quantity' => ['relative' => false, 'value' => $validated['qty']],
        ], fn ($value) => $value !== null));

        return back()->with('message', 'Cart Updated Successfully');
    }

    public function remove(string $id): RedirectResponse
    {
        Cart::remove($this->decryptId($id));

        return back();
    }

    public function destroy(string $cart): RedirectResponse
    {
        Cart::remove($cart);

        return back()->with('message', 'Cart Deleted Successfully');
    }
}
