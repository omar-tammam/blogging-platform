<?php

namespace App\Repositories\Article;

use App\Models\Article\ArticleViewer;
use App\Repositories\BaseRepository;


class ArticleViewerRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return ArticleViewer::class;
    }


}
