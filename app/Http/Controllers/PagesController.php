<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NameRequest;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\ContactUs;
use App\Models\Menu;
use App\Models\News;
use App\Models\Product;
use App\Models\Project;
use App\Models\Slider;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function __construct(private readonly ImageUploadService $uploads) {}

    // ---- Admin: menus -------------------------------------------------------

    public function menuIndex(): View
    {
        return view('manage.menu.index', [
            'menu' => Menu::all(),
            'bheading' => 'Menu List',
            'breadcrumb' => 'Menu List',
        ]);
    }

    public function addMenu(): View
    {
        return view('manage.menu.create', [
            'bheading' => 'Add Menu',
            'breadcrumb' => 'Add Menu',
        ]);
    }

    public function createMenu(NameRequest $request): RedirectResponse
    {
        Menu::create(['name' => $request->input('name')]);

        return back()->with('alert', 'success')->with('message', 'Menu created successfully');
    }

    public function editMenu(string $id): View
    {
        return view('manage.menu.edit', [
            'menu' => Menu::findOrFail($this->decryptId($id)),
            'bheading' => 'Edit Menu',
            'breadcrumb' => 'Edit Menu',
        ]);
    }

    public function updateMenu(NameRequest $request, string $id): RedirectResponse
    {
        Menu::findOrFail($this->decryptId($id))->update(['name' => $request->input('name')]);

        return back()->with('alert', 'success')->with('message', 'Menu updated successfully');
    }

    // ---- Admin: sliders -----------------------------------------------------

    public function sliderIndex(): View
    {
        return view('manage.slider.index', [
            'sliders' => Slider::all(),
            'bheading' => 'Sliders',
            'breadcrumb' => 'Sliders',
        ]);
    }

    public function createSlider(): View
    {
        return view('manage.slider.create', [
            'bheading' => 'Create Slider',
            'breadcrumb' => 'Create Slider',
        ]);
    }

    public function storeSlider(SliderRequest $request): RedirectResponse
    {
        Slider::create([
            'name' => $request->input('name'),
            'image' => $this->uploads->store($request->file('image'), 'sliders', 1080, 500),
        ]);

        return back()->with('alert', 'success')->with('message', 'Slider Added Successfully');
    }

    public function deleteSlider(string $id): RedirectResponse
    {
        Slider::findOrFail($this->decryptId($id))->delete();

        return back()->with('alert', 'error')->with('message', 'Slider deleted Successfully');
    }

    // ---- Public pages -------------------------------------------------------

    public function show(string $slug): mixed
    {
        return match ($slug) {
            'home' => app(HomeController::class)->index(),
            'projects' => view('users.pages.projects', [
                'projects' => Project::latest()->get(),
            ]),
            'products' => view('users.pages.products', [
                'prod' => Product::latest()->take(5)->get(),
                'pro' => Product::orderBy('sale_price')->take(10)->get(),
                'products' => Product::latest()->paginate(20),
            ]),
            'news' => view('users.pages.news', [
                // status 1 = disabled in the admin (NewsController::status).
                'news' => News::published()->latest()->get(),
            ]),
            'contacts' => view('users.pages.contact', [
                'contact' => ContactUs::latest()->first(),
            ]),
            // The old app linked an "about" page but never had its view.
            default => abort(404),
        };
    }

    public function newsDetails(News $news): View
    {
        // Articles disabled in the admin are not public.
        abort_if((int) $news->status === 1, 404);

        return view('users.pages.news-details', [
            'news' => $news,
            'moreNews' => News::published()->whereKeyNot($news->id)->latest()->take(3)->get(),
        ]);
    }

    /**
     * Old /news/details/{encrypted id} links → 301 to /news/{slug}.
     */
    public function legacyNewsDetails(string $id): RedirectResponse
    {
        return redirect()->route('news.details', News::findOrFail($this->decryptId($id)), 301);
    }

    public function projectsDetails(Project $project): View
    {
        return view('users.pages.project-details', [
            'project' => $project,
            'moreProjects' => Project::whereKeyNot($project->id)->latest()->take(3)->get(),
        ]);
    }

    /**
     * Old /projects/details/{encrypted id} links → 301 to /project/{slug}.
     */
    public function legacyProjectDetails(string $id): RedirectResponse
    {
        return redirect()->route('projects.details', Project::findOrFail($this->decryptId($id)), 301);
    }
}
