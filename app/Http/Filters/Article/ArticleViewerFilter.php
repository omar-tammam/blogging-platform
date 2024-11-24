<?php

namespace App\Http\Filters\Article;

use App\Http\Filters\Filter;

class ArticleViewerFilter extends Filter
{

    /**
     * Filter by articleId
     * @param int $articleId
     * @return mixed
     */
    public function articleId(int $articleId): mixed
    {
        return $this->builder->where('article_id', $articleId);
    }

    /**
     * Filter by viewerIp
     * @param string $viewerIp
     * @return mixed
     */
    public function viewerIp(string $viewerIp): mixed
    {
        return $this->builder->where('viewer_ip', $viewerIp);
    }

}
