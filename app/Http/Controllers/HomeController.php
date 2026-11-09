<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountDetailsRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    // ---- Shop -----------------------------------------------------------

    public function index(): View
    {
        return view('users.home', [
            'recents' => $this->recentlyViewed(5),
            'title' => 'Index',
            'products' => Product::latest()->simplePaginate(50),
            'sliders' => Slider::all(),
            'categories' => Category::take(4)->get(),
        ]);
    }

    /**
     * Old /products/details/{encrypted id} links → 301 to /product/{slug}.
     */
    public function legacyProductDetails(string $id): RedirectResponse
    {
        $product = Product::findOrFail($this->decryptId($id));

        return redirect()->route('product-details', $product, 301);
    }

    public function productDetails(Product $product): View
    {
        session()->push('products.recently_viewed', $product->getKey());
        // `views` can be NULL in legacy rows, so don't use increment() (NULL + 1 = NULL in SQL).
        $product->update(['views' => (int) $product->views + 1]);

        return view('users.products.products', [
            'rating' => Review::where('product_id', $product->id)->latest()->take(3)->get(),
            'recent' => $this->recentlyViewed(5),
            'related' => Product::where('category_id', $product->category_id)->take(5)->get(),
            'title' => $product->name,
            'product' => $product,
            'products' => Product::latest()->take(5)->get(),
            'breadcrumb' => $product->name,
            'category' => Category::latest()->get(),
        ]);
    }

    public function category(string $id): View
    {
        return view('users.pages.products', [
            'products' => Product::where('category_id', $this->decryptId($id))->simplePaginate(20),
            'prod' => Product::latest()->take(5)->get(),
        ]);
    }

    public function search(Request $request): View
    {
        $search = (string) $request->query('search', '');

        return view('users.pages.products', [
            'products' => Product::where('name', 'like', '%'.$search.'%')->simplePaginate(18)->withQueryString(),
            'prod' => Product::latest()->take(5)->get(),
            'search' => $search,
        ]);
    }

    public function addReview(ReviewRequest $request, Product $product): RedirectResponse
    {
        Review::create([
            'product_id' => $product->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rating' => $request->integer('rated'),
            'description' => $request->input('description'),
        ]);

        return back();
    }

    // ---- Account --------------------------------------------------------

    public function account(Request $request): View
    {
        return view('users.accounts.index', [
            'addresses' => $request->user()->shippings()->where('is_default', 1)->get(),
            'title' => 'My Details',
        ]);
    }

    public function userDetails(): View
    {
        return view('users.accounts.profile');
    }

    public function updateDetails(AccountDetailsRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        return back()->with('success', 'Details Updated Successfully');
    }

    public function myOrders(Request $request): View
    {
        $orders = DB::table('orders')
            ->join('order_items', 'orders.order_No', '=', 'order_items.order_No')
            ->where('orders.user_id', $request->user()->id)
            ->latest('orders.created_at')
            ->paginate(8);

        return view('users.accounts.orders', compact('orders'));
    }

    public function orderDetails(Request $request, string $id): View
    {
        $orderNo = $this->decryptId($id);

        return view('users.accounts.orderDetails', [
            'orders' => Order::where('order_No', $orderNo)->where('user_id', $request->user()->id)->firstOrFail(),
            'items' => OrderItem::where('order_No', $orderNo)->where('user_id', $request->user()->id)->get(),
        ]);
    }

    public function myTransactions(Request $request): View
    {
        return view('users.accounts.transactions', [
            'transactions' => Transaction::where('user_id', $request->user()->id)->latest()->simplePaginate(10),
            'user' => $request->user(),
        ]);
    }

    public function recentViews(): View
    {
        return view('users.accounts.recent-view', ['recent' => $this->recentlyViewed(10)]);
    }

    public function notifications(Request $request): View
    {
        $request->user()->update(['notifyCount' => 0]);

        return view('users.accounts.notify', [
            'notify' => $request->user()->userNotifications()->latest()->get(),
        ]);
    }

    public function deleteNotification(Request $request, string $id): RedirectResponse
    {
        $request->user()->userNotifications()->whereKey($this->decryptId($id))->delete();

        return back();
    }

    // ---- Address book ---------------------------------------------------

    public function addresses(Request $request): View
    {
        return view('users.accounts.address', [
            'addresses' => $request->user()->shippings()->paginate(4),
            'title' => 'My Details',
        ]);
    }

    public function createAddress(Request $request): View
    {
        return view('users.accounts.add-address', [
            'addresses' => $request->user()->shippings()->get(),
        ]);
    }

    public function editAddress(Request $request, string $id): View
    {
        return view('users.accounts.update-address', [
            'addresses' => $request->user()->shippings()->whereKey($this->decryptId($id))->get()->whenEmpty(fn () => abort(404)),
        ]);
    }

    /**
     * Create an address, or update one when the form sends `update`.
     * The {id} segment is the address id when updating (old URL shape kept).
     */
    public function saveAddress(AddressRequest $request, string $id): RedirectResponse
    {
        $user = $request->user();

        if ($request->filled('update')) {
            $address = $user->shippings()->findOrFail($id);
            $address->fill($request->shippingAttributes());

            if ($request->boolean('default')) {
                $user->shippings()->where('is_default', 1)->update(['is_default' => 0]);
                $address->is_default = 1;
            } else {
                $address->is_default = 0;
            }

            $address->save();

            return back()->with('success', 'Address updated successfully');
        }

        $user->shippings()->create($request->shippingAttributes() + [
            'is_default' => $user->shippings()->where('is_default', 1)->exists() ? 0 : 1,
        ]);

        return back()->with('success', 'Address Added successfully');
    }

    public function deleteAddress(Request $request, string $id): RedirectResponse
    {
        $request->user()->shippings()->findOrFail($this->decryptId($id))->delete();

        return back()->with('error', 'Address Deleted Successfully');
    }

    /**
     * Products the visitor viewed most recently (stored in the session).
     *
     * @return Collection<int, Product>|array{}
     */
    private function recentlyViewed(int $limit): Collection|array
    {
        $ids = session('products.recently_viewed');

        if (! is_array($ids) || $ids === []) {
            return [];
        }

        return Product::whereIn('id', array_slice(array_unique($ids), -$limit, $limit))->latest()->take($limit)->get();
    }
}
