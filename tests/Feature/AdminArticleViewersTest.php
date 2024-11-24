<?php

namespace Tests\Feature;

use App\Models\Article\Article;
use App\Models\Article\ArticleViewer;

use Tests\TestCase;

class AdminArticleViewersTest extends TestCase
{
    /** @test */
    public function admin_can_view_article_viewers_list_with_pagination()
    {
        $article = Article::factory()->create();
        $viewLogs = ArticleViewer::factory()->count(15)->create([
            'article_id' => $article->id,
        ]);

        $response = $this->getJson(self::$baseAPIUrl . "/admin/articles-viewers?articleId={$article->id}&page=1&perPage=10");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'data' => [
                    'items' => [
                        '*' => [
                            'logId',
                            'viewerIp',
                            'articleId',
                            'createdAt',
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
            ])
            ->assertJsonFragment([
                'currentPage' => 1,
                'perPage' => 10,
            ]);

        $responseData = $response->json('data');

        $this->assertEquals($viewLogs->count(), $responseData['pagination']['total']);
        foreach ($responseData['items'] as $item) {
            $this->assertEquals($article->id, $item['articleId']);
        }
    }
}
