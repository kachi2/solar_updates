<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_404_page_offers_search_and_categories(): void
    {
        $this->createProduct(); // creates the "Inverters" category

        $this->get('/product/this-does-not-exist')
            ->assertNotFound()
            ->assertSee("We can't find that page", false)
            ->assertSee('name="search"', false)
            ->assertSee('Go to homepage')
            ->assertSee('Popular categories')
            ->assertSee('/category/inverters', false)
            ->assertDontSee('fa-check', false);
    }

    public function test_419_page_explains_the_expired_session(): void
    {
        $html = view('errors.419')->render();

        $this->assertStringContainsString('Your session expired', $html);
        $this->assertStringContainsString('Go back and try again', $html);
    }

    public function test_500_page_renders_without_touching_the_database(): void
    {
        $queries = 0;
        DB::listen(function () use (&$queries) {
            $queries++;
        });

        $html = view('errors.500')->render();

        $this->assertSame(0, $queries, 'The 500 page must not query the database.');
        $this->assertStringContainsString('Something went wrong on our side', $html);
        $this->assertStringContainsString('wa.me/', $html);
    }
}
