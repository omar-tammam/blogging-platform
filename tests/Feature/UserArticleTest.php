<?php

namespace Tests\Feature;

use App\Models\Article\Article;
use App\Models\Category\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserArticleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with test data
        $this->seed_test_data();
    }

    /**
     * Seed test data for the tests.
     */
    private function seed_test_data()
    {
        // Create categories
        $categories = Category::factory()->count(3)->create();

        // Create articles and associate categories
        Article::factory()->count(7)->create()->each(function ($article, $index) use ($categories) {
            if ($index % 2 === 0) {
                $article->categories()->attach($categories->random());
            }
        });
    }

    /** @test */
    public function user_can_list_articles()
    {
        $response = $this->getJson(self::$baseAPIUrl . '/user/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'title',
                            'categories',
                        ],
                    ],
                    'pagination' => [
                        'total',
                        'perPage',
                        'currentPage',
                        'nextPage',
                        'previousPage',
                    ],
                ],
                'code',
            ])
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 7, // Total number of articles seeded
                        'perPage' => 10,
                        'currentPage' => 1,
                    ],
                ],
            ]);
    }

    /** @test */
    public function user_can_search_articles()
    {
        $articleTitle = Str::random(10);

        $article = Article::factory()->create(['title' => $articleTitle]);
        $search_query = 'test updated';

        $response = $this->getJson(self::$baseAPIUrl . '/user/articles', ['search' => $articleTitle]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $articleTitle]);
    }

    /** @test */
    public function user_can_filter_articles_by_categories()
    {
        $category = Category::first();

        $response = $this->getJson(self::$baseAPIUrl . '/user/articles', ['categories[]' => $category->id]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'categories' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'slug',
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->assertJsonFragment(['id' => $category->id]);
    }

    /** @test */
    public function user_can_preview_article()
    {
        $article = Article::first();

        $response = $this->getJson(self::$baseAPIUrl . '/user/articles/' . $article->id);

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                ],
                'code' => 200,
            ]);
    }

    /** @test */
    public function article_view_is_logged_in_database()
    {
        $article = Article::first();

        $response = $this->getJson(self::$baseAPIUrl . '/user/articles/' .$article->id);

        $response->assertStatus(200);

        $this->assertDatabaseHas('article_viewers', [
            'article_id' => $article->id,
        ]);
    }
}
