<?php

namespace App\Services\Article;

use App\Repositories\BaseRepository;
use App\Repositories\Article\ArticleRepository;
use App\Services\BaseService;

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



}
