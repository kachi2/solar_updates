<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdminPasswordRequest;
use App\Http\Requests\Admin\ExchangeRateRequest;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Http\Requests\Admin\ProjectRequest;
use App\Http\Requests\Admin\PushNotificationRequest;
use App\Mail\DispatchedMail;
use App\Models\AdminNotify;
use App\Models\CurrencyExchange;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Project;
use App\Models\Shipping;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function __construct(private readonly ImageUploadService $uploads) {}

    public function index(): View
    {
        return view('manage.users.index', [
            'bheading' => 'Index',
            'breadcrumb' => 'Index',
            'products' => Product::count(),
            'users' => User::count(),
            'order' => Order::count(),
            'orders' => Order::latest()->take(5)->get(),
            'sales' => Order::sum('amount'),
        ]);
    }

    public function orders(): View
    {
        return view('manage.sales.orders', [
            'orders' => Order::latest()->get(),
            'bheading' => 'Users Orders',
            'breadcrumb' => 'Orders',
        ]);
    }

    public function orderDetails(string $id): View
    {
        return view('manage.sales.orderDetails', [
            'ordersItems' => OrderItem::where('order_No', $this->decryptId($id))->get(),
            'bheading' => 'Order Details',
            'breadcrumb' => 'Order details',
        ]);
    }

    public function shipping(string $id): View
    {
        $order = Order::where('order_No', $this->decryptId($id))->firstOrFail();

        return view('manage.sales.shipping', [
            'shipping' => Shipping::find($order->shipping_id),
            'bheading' => 'Shipping Address',
            'breadcrumb' => 'Shipping Address',
        ]);
    }

    public function status(string $id): View
    {
        return view('manage.sales.status', [
            'order' => Order::where('order_No', $this->decryptId($id))->firstOrFail(),
            'bheading' => 'Update Status',
            'breadcrumb' => 'Update Status',
        ]);
    }

    public function updateStatus(OrderStatusRequest $request, string $id): RedirectResponse
    {
        $order = Order::where('order_No', $this->decryptId($id))->firstOrFail();
        $wasDispatched = (int) $order->dispatch_status === 1;

        $attributes = [
            'is_delivered' => $request->integer('delivery'),
            'dispatch_status' => $request->integer('dispatch'),
            'is_paid' => $request->integer('payment'),
        ];

        if ($request->integer('payment') === 1) {
            $attributes['payment_ref'] = 'SFSL'.random_int(111111, 999999);
        }

        Order::where('order_No', $order->order_No)->update($attributes);

        if (! $wasDispatched && $request->integer('dispatch') === 1) {
            $this->sendDispatchedMail($order);
        }

        return back()->with('alert', 'success')->with('message', 'Status Updated Successfully');
    }

    public function transactions(): View
    {
        return view('manage.sales.transactions', [
            'transactions' => Transaction::latest()->get(),
            'bheading' => 'Payment Transactions',
            'breadcrumb' => 'Transactions',
        ]);
    }

    public function users(): View
    {
        return view('manage.users.users', [
            'users' => User::latest()->get(),
            'bheading' => 'Users',
            'breadcrumb' => 'Users',
        ]);
    }

    public function userOrders(string $id): View
    {
        return view('manage.sales.orders', [
            'orders' => Order::where('user_id', $this->decryptId($id))->get(),
            'bheading' => 'User\'s Orders',
            'breadcrumb' => 'Order',
        ]);
    }

    public function userTransactions(string $id): View
    {
        return view('manage.sales.transactions', [
            'transactions' => Transaction::where('user_id', $this->decryptId($id))->latest()->get(),
            'bheading' => 'User\'s Transactions',
            'breadcrumb' => 'Transactions',
        ]);
    }

    public function download(string $image): BinaryFileResponse
    {
        $path = public_path('images/products/'.basename((string) $this->decryptId($image)));
        abort_unless(is_file($path), 404);

        return response()->download($path);
    }

    public function notify(): View
    {
        return view('manage.users.notify', [
            'bheading' => 'Send Notification',
            'breadcrumb' => 'Send Notification',
        ]);
    }

    public function pushNotify(PushNotificationRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            User::query()->each(function (User $user) use ($request) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $request->input('title'),
                    'message' => 'Dear '.$user->name.' '.$request->input('message'),
                ]);
                $user->increment('notifyCount');
            });
        });

        return back()->with('alert', 'success')->with('message', 'Notification sent Successfully');
    }

    public function markNotificationRead(string $id): RedirectResponse
    {
        AdminNotify::whereKey($this->decryptId($id))->update(['is_read' => 1]);

        return back();
    }

    public function clearNotifications(): RedirectResponse
    {
        AdminNotify::query()->delete();

        return back()->with('alert', 'success')->with('message', 'Notification clear');
    }

    public function analytics(): View
    {
        $activeUsers = User::where('updated_at', '>=', now()->subMinutes(10))->latest()->get();
        $newToday = User::where('created_at', '>=', today())->latest()->get();
        $newThisWeek = User::where('created_at', '>=', now()->startOfWeek())->latest()->get();
        $recentOrders = Order::where('created_at', '>=', now()->subMinutes(10))->latest()->get();

        return view('manage.analytical', [
            'users' => $activeUsers,
            'active' => $activeUsers->count(),
            'recentActive' => $activeUsers,
            'recent' => $activeUsers->count(),
            'new_users' => $newToday,
            'thisweek' => $newThisWeek,
            'today' => $newToday->count(),
            'week' => $newThisWeek->count(),
            'orders' => $recentOrders,
            'order' => $recentOrders->count(),
            'av_orders' => $recentOrders,
            'tt_order' => $recentOrders->count(),
            'bheading' => 'Analytics',
            'breadcrumb' => 'Analytics',
        ]);
    }

    public function profile(): View
    {
        return view('manage.profile', [
            'admin' => auth('admin')->user(),
            'bheading' => 'Admin Profile',
            'breadcrumb' => 'Update Admin Details',
        ]);
    }

    public function updateProfile(AdminPasswordRequest $request): RedirectResponse
    {
        $request->user('admin')->update(['password' => bcrypt($request->input('password'))]);

        return back()->with('alert', 'alert-success')->with('pass', 'Password Updated Successfully');
    }

    public function projects(): View
    {
        return view('manage.projects.index', [
            'projects' => Project::latest()->get(),
            'bheading' => 'Projects',
            'breadcrumb' => 'Projects Index',
        ]);
    }

    public function createProject(): View
    {
        return view('manage.projects.create', [
            'bheading' => 'Projects',
            'breadcrumb' => 'Create Projects',
        ]);
    }

    public function storeProject(ProjectRequest $request): RedirectResponse
    {
        $project = new Project($request->only('title', 'content'));
        $this->attachProjectImages($project, $request);
        $project->save();

        return back()->with('alert', 'success')->with('message', 'Project added Successfully');
    }

    public function editProject(string $id): View
    {
        return view('manage.projects.edit', [
            'project' => Project::findOrFail($this->decryptId($id)),
            'bheading' => 'Edit Projects',
            'breadcrumb' => 'Edit Projects',
        ]);
    }

    public function updateProject(ProjectRequest $request, string $id): RedirectResponse
    {
        $project = Project::findOrFail($this->decryptId($id));
        $project->fill($request->only('title', 'content'));
        $this->attachProjectImages($project, $request);
        $project->save();

        return back()->with('alert', 'success')->with('message', 'Project Updated Successfully');
    }

    public function deleteProject(string $id): RedirectResponse
    {
        Project::findOrFail($this->decryptId($id))->delete();

        return back()->with('alert', 'error')->with('message', 'Project deleted Successfully');
    }

    public function updateExchangeRate(ExchangeRateRequest $request): RedirectResponse
    {
        $rate = $request->integer('rate');

        DB::transaction(function () use ($rate) {
            CurrencyExchange::firstOrFail()->update(['rate' => $rate]);
            Product::query()->each(fn (Product $product) => $product->update([
                'exchange_rate' => $product->sale_price / $rate,
            ]));
        });

        return back()->with('alert', 'success')->with('message', 'All Products Exchange Rate Updated Successfully');
    }

    private function attachProjectImages(Project $project, ProjectRequest $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $images = $this->uploads->storeMany($request->file('images'), 'projects', 500, 550);
        $project->images = json_encode($images);
        $project->image = $images[0];
    }

    private function sendDispatchedMail(Order $order): void
    {
        $shipping = Shipping::find($order->shipping_id);

        if (! $shipping?->receiver_email) {
            return;
        }

        Mail::to($shipping->receiver_email)->send(new DispatchedMail([
            'order_No' => $order->order_No,
            'name' => $shipping->receiver_name,
            'amount' => $order->amount,
            'email' => $shipping->receiver_email,
            'receiver_name' => $shipping->receiver_name,
            'phone' => $shipping->receiver_phone,
            'address' => $shipping->address,
            'delivery_method' => $shipping->delivery_method,
            'order_items' => OrderItem::where('order_No', $order->order_No)->get(),
        ]));
    }
}
