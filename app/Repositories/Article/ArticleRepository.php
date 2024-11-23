<?php

namespace App\Repositories\Article;

use App\Models\Article\Article;
use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Article::class;
    }

}
