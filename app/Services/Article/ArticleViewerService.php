<?php

namespace App\Services\Article;

use App\Http\Filters\Filter;
use App\Repositories\Article\ArticleViewerRepository;
use App\Repositories\BaseRepository;
use App\Services\BaseService;

class ArticleViewerService extends BaseService
{
    /**
     * @var ArticleViewerRepository
     */
    protected BaseRepository $repository;

    public function __construct(
        ArticleViewerRepository $repository,
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
        return $this->repository->paginate($page, $perPage, $filter);
    }

    /**
     * @param int $articleId
     * @param string $viewerIp
     */
    public function addViewer(int $articleId, string $viewerIp): void
    {
        $this->repository->add(['article_id' => $articleId, 'viewer_ip' => $viewerIp]);
    }

}
