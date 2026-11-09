<?php

namespace App\Http\Controllers;

use App\Exceptions\UserFacingException;
use App\Http\Requests\AccountDetailsRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Repositories\AddressRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ContentRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\TransactionRepository;
use App\Services\AddressService;
use App\Services\RecentlyViewedProducts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly CategoryRepository $categories,
        private readonly RecentlyViewedProducts $recentlyViewed,
    ) {}

    // ---- Shop -----------------------------------------------------------

    public function index(ContentRepository $content): View
    {
        return view('users.home', [
            'recents' => $this->recentlyViewed->latest(5),
            'title' => 'Index',
            'products' => $this->products->latestPaginated(config('sofar.per_page.home_products')),
            'sliders' => $content->sliders(),
            'categories' => $this->categories->featured(4),
        ]);
    }

    /**
     * Old /products/details/{encrypted id} links → 301 to /product/{slug}.
     */
    public function legacyProductDetails(string $id): RedirectResponse
    {
        return redirect()->route('product-details', $this->products->findOrFail($this->decryptId($id)), 301);
    }

    public function productDetails(Product $product, ReviewRepository $reviews): View
    {
        $this->recentlyViewed->remember($product);
        $this->products->recordView($product);

        $summary = $reviews->summaryFor($product);

        return view('users.products.products', [
            'rating' => $reviews->latestFor($product, 3),
            'reviewCount' => $summary['count'],
            'averageRating' => $summary['average'],
            'recent' => $this->recentlyViewed->latest(4),
            'related' => $this->products->related($product, 4),
            'title' => $product->name,
            'product' => $product,
            'breadcrumb' => $product->name,
        ]);
    }

    /**
     * /category/{slug}. Old /category/{encrypted id} links (they start with "eyJ")
     * share the same path, so they are recognised here and redirected permanently.
     */
    public function category(string $category): View|RedirectResponse
    {
        if (str_starts_with($category, 'eyJ')) {
            return redirect()->route('user.category', $this->categories->findOrFail($this->decryptId($category)), 301);
        }

        $category = $this->categories->findBySlugOrFail($category);

        return view('users.pages.products', [
            'products' => $this->products->inCategory($category, config('sofar.per_page.shop')),
            'prod' => $this->products->latest(5),
            'currentCategory' => $category,
        ]);
    }

    public function search(Request $request): View
    {
        $search = (string) $request->query('search', '');

        return view('users.pages.products', [
            'products' => $this->products->search($search, config('sofar.per_page.search')),
            'prod' => $this->products->latest(5),
            'search' => $search,
        ]);
    }

    public function addReview(ReviewRequest $request, Product $product, ReviewRepository $reviews): RedirectResponse
    {
        $reviews->create([
            'product_id' => $product->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'rating' => $request->integer('rated'),
            'description' => $request->input('description'),
        ]);

        return redirect()->to(route('product-details', $product).'#pdTabs')
            ->with('review_status', 'Thank you! Your review has been posted.');
    }

    // ---- Account --------------------------------------------------------

    public function account(Request $request, AddressRepository $addresses, OrderRepository $orders): View
    {
        $user = $request->user();

        return view('users.accounts.index', [
            'addresses' => $addresses->defaultsFor($user),
            'recentOrders' => $orders->recentForUser($user, 3),
            'orderCount' => $orders->countForUser($user),
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

    public function myOrders(Request $request, OrderRepository $orders): View
    {
        return view('users.accounts.orders', [
            'orders' => $orders->paginateForUser($request->user(), config('sofar.per_page.orders')),
        ]);
    }

    public function orderDetails(Request $request, string $id, OrderRepository $orders): View
    {
        $orderNo = $this->decryptId($id);

        return view('users.accounts.orderDetails', [
            'orders' => $orders->findForUserOrFail($request->user(), $orderNo),
            'items' => $orders->itemsForUser($request->user(), $orderNo),
        ]);
    }

    public function myTransactions(Request $request, TransactionRepository $transactions): View
    {
        return view('users.accounts.transactions', [
            'transactions' => $transactions->paginateForUser($request->user(), config('sofar.per_page.payments')),
            'user' => $request->user(),
        ]);
    }

    public function recentViews(): View
    {
        return view('users.accounts.recent-view', ['recent' => $this->recentlyViewed->latest(10)]);
    }

    public function notifications(Request $request, NotificationRepository $notifications): View
    {
        $request->user()->update(['notifyCount' => 0]);

        return view('users.accounts.notify', [
            'notify' => $notifications->forUser($request->user()),
        ]);
    }

    public function deleteNotification(Request $request, string $id, NotificationRepository $notifications): RedirectResponse
    {
        $notifications->deleteForUser($request->user(), $this->decryptId($id));

        return back();
    }

    // ---- Address book ---------------------------------------------------

    public function addresses(Request $request, AddressRepository $addresses): View
    {
        return view('users.accounts.address', [
            'addresses' => $addresses->paginateFor($request->user(), config('sofar.per_page.addresses')),
            'title' => 'My Details',
        ]);
    }

    public function createAddress(Request $request, AddressRepository $addresses): View
    {
        return view('users.accounts.add-address', [
            'addresses' => $addresses->allFor($request->user()),
        ]);
    }

    public function editAddress(Request $request, string $id, AddressRepository $addresses, AddressService $addressService): View
    {
        $user = $request->user();

        return view('users.accounts.update-address', [
            'addresses' => collect([$addresses->findForOrFail($user, $this->decryptId($id))]),
            'canRemove' => $addressService->canRemove($user),
        ]);
    }

    /**
     * Create an address, or update one when the form sends `update`.
     * The {id} segment is the address id when updating (old URL shape kept).
     */
    public function saveAddress(AddressRequest $request, string $id, AddressRepository $addresses, AddressService $addressService): RedirectResponse
    {
        $user = $request->user();

        if ($request->filled('update')) {
            $address = $addresses->findForOrFail($user, $id);
            $addressService->update($user, $address, $request->shippingAttributes(), $request->boolean('default'));

            return redirect()->route('users.address')->with('success', 'Address updated.');
        }

        $addressService->add($user, $request->shippingAttributes(), $request->boolean('default'));

        return redirect()->route('users.address')->with('success', 'Address added.');
    }

    public function deleteAddress(Request $request, string $id, AddressRepository $addresses, AddressService $addressService): RedirectResponse
    {
        $user = $request->user();
        $address = $addresses->findForOrFail($user, $this->decryptId($id));

        try {
            $addressService->remove($user, $address);
        } catch (UserFacingException $e) {
            return $this->failWith($e);
        }

        return back()->with('success', 'Address removed.');
    }

    public function setDefaultAddress(Request $request, string $id, AddressRepository $addresses, AddressService $addressService): RedirectResponse
    {
        $user = $request->user();
        $addressService->setDefault($user, $addresses->findForOrFail($user, $this->decryptId($id)));

        return back()->with('success', 'Default delivery address updated.');
    }
}
