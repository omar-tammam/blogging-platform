<?php

namespace App\Repositories\Category;

use App\Models\Article\Article;
use App\Models\Category\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Category::class;
    }

}
