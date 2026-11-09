<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesShopData;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use CreatesShopData, RefreshDatabase;

    public function test_product_page_is_reached_by_slug(): void
    {
        $product = $this->createProduct(['name' => '10kW Hybrid Inverter']);

        $this->assertSame('10kw-hybrid-inverter', $product->slug);
        $this->assertStringEndsWith('/product/10kw-hybrid-inverter', route('product-details', $product));

        $this->get('/product/10kw-hybrid-inverter')->assertOk()->assertSee('10kW Hybrid Inverter');
    }

    public function test_old_encrypted_links_redirect_permanently(): void
    {
        $product = $this->createProduct();

        $this->get('/products/details/'.encrypt($product->id))
            ->assertStatus(301)
            ->assertRedirect(route('product-details', $product));
    }

    public function test_category_page_uses_slug_and_old_links_redirect(): void
    {
        $product = $this->createProduct(['name' => 'Hybrid 5kW']);
        $category = $product->category;

        $this->assertSame('inverters', $category->slug);
        $this->assertStringEndsWith('/category/inverters', route('user.category', $category));

        $this->get('/category/inverters')->assertOk()->assertSee('Hybrid 5kW');
        $this->get('/category/'.encrypt($category->id))
            ->assertStatus(301)
            ->assertRedirect(route('user.category', $category));
        $this->get('/category/no-such-category')->assertNotFound();
        $this->get('/category/eyJnotEncrypted')->assertNotFound();
    }

    public function test_project_page_uses_slug_and_old_links_redirect(): void
    {
        $project = Project::create([
            'title' => 'Solar installation @ Ikeja GRA',
            'content' => '<p><span style="font-size: 13px">25kva installation</span></p>',
            'image' => 'main.jpg',
            'images' => json_encode(['main.jpg', 'second.jpg', 'third.jpg']),
        ]);
        $other = Project::create(['title' => 'Ibadan duplex', 'image' => 'x.jpg']);

        $this->assertSame('solar-installation-at-ikeja-gra', $project->slug);

        $this->get('/project/solar-installation-at-ikeja-gra')
            ->assertOk()
            ->assertSee('Solar installation @ Ikeja GRA')
            ->assertSee('3 photos')
            ->assertSee('data-fancybox="project-gallery"', false)
            ->assertSee('25kva installation')
            ->assertSee('More projects')
            ->assertSee(route('projects.details', $other));

        $this->get('/projects/details/'.encrypt($project->id))
            ->assertStatus(301)
            ->assertRedirect(route('projects.details', $project));

        $this->get('/project/no-such-project')->assertNotFound();
    }

    public function test_news_page_features_newest_and_hides_disabled_posts(): void
    {
        $adminId = $this->createAdmin()->id;
        $old = News::create(['admin_id' => $adminId, 'title' => 'Older post', 'content' => '<p>Old news</p>', 'image' => 'a.jpg']);
        $old->forceFill(['created_at' => now()->subMonth()])->save();
        News::create(['admin_id' => $adminId, 'title' => 'Newest post', 'content' => '<p><span style="font-size:13px">Office opens in Lagos</span></p>', 'image' => 'b.jpg']);
        News::create(['admin_id' => $adminId, 'title' => 'Hidden post', 'content' => 'x', 'status' => 1]);

        $html = $this->get(route('pages', 'news'))
            ->assertOk()
            ->assertSee('News &amp; updates', false)
            ->assertSee('Newest post')
            ->assertSee('Office opens in Lagos')
            ->assertSee('More news')
            ->assertSee('Older post')
            ->assertDontSee('Hidden post')
            ->getContent();

        $this->assertLessThan(strpos($html, 'Older post'), strpos($html, 'Newest post'));
    }

    public function test_news_article_uses_slug_and_old_links_redirect(): void
    {
        $adminId = $this->createAdmin()->id;
        $article = News::create(['admin_id' => $adminId, 'title' => 'SofarSolar Open office in Lagos', 'content' => '<p>Arena Oshodi</p>', 'image' => 'cover.jpg', 'images' => json_encode(['cover.jpg', 'cover.jpg'])]);
        $other = News::create(['admin_id' => $adminId, 'title' => 'New battery range', 'content' => 'x']);
        $hidden = News::create(['admin_id' => $adminId, 'title' => 'Draft', 'content' => 'x', 'status' => 1]);

        $this->assertSame('sofarsolar-open-office-in-lagos', $article->slug);

        $this->get('/news/sofarsolar-open-office-in-lagos')
            ->assertOk()
            ->assertSee('SofarSolar Open office in Lagos')
            ->assertSee('Arena Oshodi')
            ->assertDontSee('data-fancybox="article-gallery"', false) // gallery only repeated the cover
            ->assertSee('More news')
            ->assertSee(route('news.details', $other))
            ->assertDontSee('Draft');

        $this->get('/news/details/'.encrypt($article->id))
            ->assertStatus(301)
            ->assertRedirect(route('news.details', $article));

        $this->get(route('news.details', $hidden))->assertNotFound();
        $this->get('/news/no-such-article')->assertNotFound();
    }

    public function test_unknown_slug_and_bad_legacy_id_return_404(): void
    {
        $this->get('/product/does-not-exist')->assertNotFound();
        $this->get('/products/details/garbage')->assertNotFound();
    }

    /**
     * @return array<string, mixed>
     */
    private function review(array $overrides = []): array
    {
        return $overrides + ['name' => 'Ada', 'email' => 'ada@example.com', 'rated' => 4, 'description' => 'Works great on my roof.'];
    }

    public function test_genuine_review_is_saved_and_shown(): void
    {
        $product = $this->createProduct();

        $this->post(route('review.store', $product->id), $this->review())
            ->assertRedirect(route('product-details', $product).'#pdTabs')
            ->assertSessionHas('review_status');

        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'name' => 'Ada', 'rating' => 4]);
        $this->get(route('product-details', $product))->assertSee('Works great on my roof.')->assertSee('4.0');
    }

    public function test_reviews_with_links_are_rejected(): void
    {
        $product = $this->createProduct();

        foreach (['visit https://spam.example now', 'see www.spam.example', '<a href="x">darknet</a>', '[url=x]x[/url]'] as $spam) {
            $this->post(route('review.store', $product->id), $this->review(['description' => $spam]))
                ->assertSessionHasErrors('description');
        }

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_honeypot_blocks_bots(): void
    {
        $product = $this->createProduct();

        $this->post(route('review.store', $product->id), $this->review(['website' => 'http://bot.example']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_reviews_are_rate_limited(): void
    {
        $product = $this->createProduct();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('review.store', $product->id), $this->review())->assertRedirect();
        }

        $this->post(route('review.store', $product->id), $this->review())->assertStatus(429);
        $this->assertDatabaseCount('reviews', 5);
    }

    public function test_duplicate_names_get_unique_slugs_and_renames_keep_the_slug(): void
    {
        $first = $this->createProduct(['name' => 'Solar Street Light 40w']);
        $second = Product::create($first->only('category_id', 'sub_category_id', 'price', 'sale_price') + ['name' => 'solar street light 40w']);

        $this->assertSame('solar-street-light-40w', $first->slug);
        $this->assertSame('solar-street-light-40w-2', $second->slug);

        $first->update(['name' => 'Solar Street Light 40w (new)']);
        $this->assertSame('solar-street-light-40w', $first->fresh()->slug);
    }
}
