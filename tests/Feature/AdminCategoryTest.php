<?php

namespace Tests\Feature;

use App\Models\Category\Category;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    /** @test */
    public function admin_can_create_a_category()
    {
        $slug = Str::random('10');
        $name = 'Test Category';
        $response = $this->postJson(self::$baseAPIUrl . '/admin/categories', [
            'name' => $name,
            'slug' => $slug,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'name' => $name,
                    'slug' => $slug,
                ],
                'code' => 201
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test_category',
        ]);
    }

    /** @test */
    public function admin_can_update_a_category()
    {
        $category = Category::factory()->create(['name' => 'Old Category']);

        $slug = $category->slug . '_updated';
        $name = 'Updated Category';

        $response = $this->putJson(self::$baseAPIUrl . "/admin/categories/{$category->id}", [
            'name' => $name,
            'slug' => $slug,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $category->id,
                    'name' => $name,
                    'slug' => $slug,
                ],
                'code' => 200
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    /** @test */
    public function admin_can_view_a_single_category()
    {
        $category = Category::factory()->create(['name' => 'Test Category']);

        $response = $this->getJson(self::$baseAPIUrl . "/admin/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'code' => 200
            ]);
    }

    /** @test */
    public function admin_can_list_categories_with_pagination()
    {
        Category::factory()->count(15)->create();

        $response = $this->getJson(self::$baseAPIUrl . '/admin/categories?page=1&perPage=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'perPage',
                        'currentPage',
                        'nextPage',
                        'previousPage',
                    ]
                ],
                'code',
            ]);

        $response->assertJsonFragment(['currentPage' => 1]);
        $response->assertJsonFragment(['perPage' => 10]);
    }

    /** @test */
    public function admin_can_delete_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(self::$baseAPIUrl . "/admin/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'version' => 1,
                'data' => [],
                'code' => 200,
                'message' => 'common.resource_deleted_successfully'
            ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
