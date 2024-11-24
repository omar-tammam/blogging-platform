<?php

namespace Database\Factories\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleViewer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleViewerFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleViewer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(), // Associate with an article
            'viewer_ip' => $this->faker->ipv4,
            'created_at' => now(),
        ];
    }
}
