<?php

namespace App\Services\Article;

use App\Http\Filters\Filter;
use App\Repositories\BaseRepository;
use App\Repositories\Article\ArticleRepository;
use App\Services\BaseService;
use Throwable;

class ArticleService extends BaseService
{
    /**
     * @var ArticleRepository
     */
    protected BaseRepository $repository;

    public function __construct(
        ArticleRepository $repository,
    ) {
        parent::__construct($repository);
    }


    /**
     * @param int $page
     * @param int $perPage
     * @param Filter $filter
     * @return mixed
     */
    public function articleViewers(int $page, int $perPage, Filter $filter): mixed
    {
        return app(ArticleViewerService::class)->articleViewers($page, $perPage, $filter);
    }


  
}
