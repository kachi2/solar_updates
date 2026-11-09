<?php

namespace App\Repositories;

use App\Models\ContactUs;
use App\Models\CurrencyExchange;
use App\Models\Menu;
use App\Models\News;
use App\Models\Project;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Collection;

/**
 * Site content: news, projects, menus, home sliders, contact details, exchange rate.
 */
class ContentRepository
{
    // ---- News ---------------------------------------------------------------

    /**
     * @return Collection<int, News>
     */
    public function publishedNews(): Collection
    {
        return News::published()->latest()->get();
    }

    /**
     * @return Collection<int, News>
     */
    public function moreNews(News $except, int $limit): Collection
    {
        return News::published()->whereKeyNot($except->id)->latest()->take($limit)->get();
    }

    public function findNewsOrFail(int|string $id): News
    {
        return News::findOrFail($id);
    }

    // ---- Projects -----------------------------------------------------------

    /**
     * @return Collection<int, Project>
     */
    public function projects(): Collection
    {
        return Project::latest()->get();
    }

    /**
     * @return Collection<int, Project>
     */
    public function moreProjects(Project $except, int $limit): Collection
    {
        return Project::whereKeyNot($except->id)->latest()->take($limit)->get();
    }

    public function findProjectOrFail(int|string $id): Project
    {
        return Project::findOrFail($id);
    }

    // ---- Menus, sliders, contact, exchange rate -------------------------------

    /**
     * Menu items that can be linked (/pages/{slug}).
     *
     * @return Collection<int, Menu>
     */
    public function linkableMenus(): Collection
    {
        return Menu::whereNotNull('slug')->where('slug', '!=', '')->get();
    }

    /**
     * @return Collection<int, Slider>
     */
    public function sliders(): Collection
    {
        return Slider::all();
    }

    public function contactDetails(): ?ContactUs
    {
        return ContactUs::latest()->first();
    }

    public function exchangeRate(): ?CurrencyExchange
    {
        return CurrencyExchange::first();
    }
}
