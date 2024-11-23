<?php

namespace App\Services\Category;

use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryRepository;
use App\Services\BaseService;

class CategoryService extends BaseService
{
    /**
     * @var CategoryRepository
     */
    protected BaseRepository $repository;

    public function __construct(
        CategoryRepository $repository,
    ) {
        parent::__construct($repository);
    }



}
