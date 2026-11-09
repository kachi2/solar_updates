<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private readonly ImageUploadService $uploads) {}

    public function index(): View
    {
        return view('manage.news.index', [
            'news' => News::all(),
            'bheading' => 'News Index',
            'breadcrumb' => 'Index',
        ]);
    }

    public function create(): View
    {
        return view('manage.news.create', [
            'bheading' => 'Create News',
            'breadcrumb' => 'Create News',
        ]);
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        $news = new News($request->only('title', 'content'));
        $news->admin_id = $request->user('admin')->id;
        $this->attachImages($news, $request);
        $news->save();

        return back()->with('alert', 'success')->with('message', 'News added Successfully');
    }

    public function edit(string $id): View
    {
        return view('manage.news.edit', [
            'news' => News::findOrFail($this->decryptId($id)),
            'bheading' => 'Edit News',
            'breadcrumb' => 'Edit News',
        ]);
    }

    public function update(NewsRequest $request, string $id): RedirectResponse
    {
        $news = News::findOrFail($this->decryptId($id));
        $news->fill($request->only('title', 'content'));
        $this->attachImages($news, $request);
        $news->save();

        return back()->with('alert', 'success')->with('message', 'News Updated Successfully');
    }

    public function status(Request $request, string $id): RedirectResponse
    {
        $status = $request->integer('status', -1);

        if (! in_array($status, [0, 1], true)) {
            return back()->with('alert', 'error')->with('message', 'An errror occured, No changes made');
        }

        News::whereKey($this->decryptId($id))->update(['status' => $status]);

        return $status === 1
            ? back()->with('alert', 'error')->with('message', 'News Disabled successfully')
            : back()->with('alert', 'success')->with('message', 'News Enabled successfully');
    }

    private function attachImages(News $news, NewsRequest $request): void
    {
        if ($request->hasFile('image')) {
            $news->image = $this->uploads->store($request->file('image'), 'news', 500, 500);
        }

        if ($request->hasFile('images')) {
            $news->images = json_encode($this->uploads->storeMany($request->file('images'), 'news', 500, 500));
        }
    }
}
