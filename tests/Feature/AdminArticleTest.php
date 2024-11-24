<?php

namespace Tests\Feature;

use App\Models\Article\Article;
use App\Models\Category\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminArticleTest extends TestCase
{
    /** @test */
    public function admin_can_create_an_article()
    {
        $category = Category::factory()->create();
        $title = 'Article Title Test';
        $content = Str::random(1000);


        $response = $this->postJson(self::$baseAPIUrl . '/admin/articles', [
            'title' => $title,
            'content' => $content,
            'categories' => [$category->id]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $response->json('data.id'),
                    'title' => $title,
                    'categories' => [
                        [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ]
                    ],
                    'viewersCount' => 0,
                ],
                'code' => 201,
            ]);

        $this->assertDatabaseHas('articles', [
            'title' => $title,
            'content' => $content,
        ]);
    }

    /** @test */
    public function admin_can_update_an_article()
    {
        $article = Article::factory()->hasCategories(1)->create();
        $newTitle = 'Article Title Test Updated';
        $newContent = Str::random(1000);

        $response = $this->putJson(self::$baseAPIUrl . "/admin/articles/{$article->id}", [
            'title' => $newTitle,
            'content' => $newContent,
            'categories' => $article->categories->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $article->id,
                    'title' => $newTitle,
                    'categories' => $article->categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ];
                    })->toArray(),
                    'viewersCount' => 0,
                ],
                'code' => 200,
            ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $newTitle,
            'content' => $newContent,
        ]);
    }

    /** @test */
    public function admin_can_view_a_single_article()
    {
        $article = Article::factory()->hasCategories(1)->create();

        $response = $this->getJson(self::$baseAPIUrl . "/admin/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'categories' => $article->categories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ];
                    })->toArray(),
                    'viewersCount' => 0,
                ],
                'code' => 200,
            ]);
    }

    /** @test */
    public function admin_can_list_articles()
    {
       $articlesCount = Article::count();


        $response = $this->getJson(self::$baseAPIUrl . '/admin/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'title',
                            'categories',
                            'viewersCount',
                        ]
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
            ]);
        
        $response->assertJson([
            'version' => 1,
            'data' => [
                'pagination' => [
                    'total' => $articlesCount,
                    'perPage' => 10,
                    'currentPage' => 1,
                ],
            ],
        ]);
    }

    /** @test */
    public function admin_can_delete_an_article()
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson(self::$baseAPIUrl . "/admin/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [],
                'code' => 200
            ]);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    private function createUser()
    {
        return User::factory()->create();
    }
}
