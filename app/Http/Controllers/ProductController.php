<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\VariationRequest;
use App\Models\Category;
use App\Models\CurrencyExchange;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const IMAGE_WIDTH = 417;

    private const IMAGE_HEIGHT = 287;

    public function __construct(private readonly ImageUploadService $uploads) {}

    public function index(): View
    {
        return view('manage.products.index', [
            'products' => Product::latest()->get(),
            'bheading' => 'Product',
            'breadcrumb' => 'Product',
        ]);
    }

    public function create(): View
    {
        return view('manage.products.create', [
            'category' => Category::all(),
            'bheading' => 'Products',
            'breadcrumb' => 'Create Product',
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $prices = array_filter(explode(',', (string) $request->input('amount')), 'strlen');
        $quantities = array_filter(explode(',', (string) $request->input('qty')), 'strlen');

        if ($request->filled('qty') && count($prices) > count($quantities)) {
            return back()->withInput()
                ->with('alert', 'error')
                ->with('message', 'Quantity and Price in Price Variation must have same numbers');
        }

        DB::transaction(function () use ($request, $prices, $quantities) {
            $product = new Product([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'sub_category_id' => 1,
                'description' => $request->input('description'),
                'price' => $request->integer('price'),
                'sale_price' => $request->integer('sale_price'),
                'admin_id' => $request->user('admin')->id,
                'exchange_rate' => $this->exchangeRate($request->integer('sale_price')),
                'percentage' => $this->discountPercentage($request->integer('price'), $request->integer('sale_price')),
            ]);

            $this->attachUploads($product, $request);
            $product->save();

            foreach ($prices as $index => $price) {
                ProductVariation::create([
                    'product_id' => $product->id,
                    'price' => $price,
                    'qty' => $quantities[$index] ?? null,
                ]);
            }
        });

        return back()->with('alert', 'success')->with('message', 'Product Added Successfully');
    }

    public function edit(string $product): View
    {
        return view('manage.products.edit', [
            'product' => Product::findOrFail($this->decryptId($product)),
            'category' => Category::all(),
            'breadcrumb' => 'Product Edit',
            'bheading' => 'Products',
        ]);
    }

    public function update(ProductRequest $request, string $product): RedirectResponse
    {
        $product = Product::findOrFail($this->decryptId($product));

        $product->fill(array_filter([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'description' => $request->input('description'),
        ], fn ($value) => $value !== null));

        $product->fill([
            'price' => $request->integer('price'),
            'sale_price' => $request->integer('sale_price'),
            'exchange_rate' => $this->exchangeRate($request->integer('sale_price')),
            'percentage' => $this->discountPercentage($request->integer('price'), $request->integer('sale_price')),
        ]);

        $this->attachUploads($product, $request);
        $product->save();

        return back()->with('alert', 'success')->with('message', 'Product Updated Successfully');
    }

    /**
     * Activate / deactivate a product (inactive products are hidden from the shop).
     */
    public function status(Request $request, string $id): RedirectResponse
    {
        $request->validate(['is_active' => ['required', 'boolean']]);
        $active = $request->boolean('is_active');

        Product::whereKey($this->decryptId($id))->update([
            'is_active' => $active,
            'status' => $active ? 0 : 1, // legacy column, kept in step
        ]);

        return $active
            ? back()->with('alert', 'success')->with('message', 'Product activated')
            : back()->with('alert', 'error')->with('message', 'Product deactivated, it is now hidden from the shop');
    }

    public function variation(string $id): View
    {
        return view('manage.products.variation', [
            'variation' => ProductVariation::where('product_id', $this->decryptId($id))->get(),
            'bheading' => 'Product',
            'breadcrumb' => 'Product',
        ]);
    }

    public function variationEdit(string $id): View
    {
        return view('manage.products.variationEdit', [
            'variation' => ProductVariation::findOrFail($this->decryptId($id)),
            'bheading' => 'Product',
            'breadcrumb' => 'Product',
        ]);
    }

    public function variationUpdate(VariationRequest $request, string $id): RedirectResponse
    {
        ProductVariation::findOrFail($this->decryptId($id))->update($request->validated());

        return back()->with('alert', 'success')->with('message', 'Variation updated successfully');
    }

    private function exchangeRate(mixed $salePrice): float
    {
        $rate = (float) CurrencyExchange::firstOrFail()->rate;

        return $rate > 0 ? (float) $salePrice / $rate : 0;
    }

    /**
     * Discount from price to sale price, in percent (0 when there is no price).
     */
    private function discountPercentage(int $price, int $salePrice): float
    {
        return $price > 0 ? ($price - $salePrice) / $price * 100 : 0;
    }

    private function attachUploads(Product $product, ProductRequest $request): void
    {
        if ($request->hasFile('specification')) {
            $product->specification = $this->uploads->storeFile($request->file('specification'), 'pdf');
        }

        if ($request->hasFile('image')) {
            $product->image = $this->uploads->store($request->file('image'), 'products', self::IMAGE_WIDTH, self::IMAGE_HEIGHT);
        }

        if ($request->hasFile('images')) {
            $product->gallery = json_encode(
                $this->uploads->storeMany($request->file('images'), 'products', self::IMAGE_WIDTH, self::IMAGE_HEIGHT)
            );
        }
    }
}
