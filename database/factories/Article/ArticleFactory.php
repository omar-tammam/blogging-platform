<?php

namespace Database\Factories\Article;

use App\Models\Article\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        $user = \App\Models\User::factory()->create();
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'created_by' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
