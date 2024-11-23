<?php

namespace App\Repositories\Article;

use App\Models\Article\Article;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Throwable;

class ArticleRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Article::class;
    }


    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function add(array $data): mixed
    {
        return $this->applyTransaction(function () use ($data) {
            $resource = parent::add(Arr::except($data, 'categories'));
            $resource->categories()->sync($data['categories']);
            return $resource->loadCount('viewers')->load('categories');
        });
    }

    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes): mixed
    {
        $resource =  parent::update($id, Arr::except($attributes, 'categories'));
        $resource->categories()->sync($attributes['categories']);
        return $resource->loadCount('viewers')->load('categories');
    }

}
