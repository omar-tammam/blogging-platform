<?php

namespace App\Http\Filters\Article;

use App\Http\Filters\Filter;

class ArticleFilter extends Filter
{

    /**
     * Filter by title
     * @param array $categories
     * @return mixed
     */
    public function categories(array $categories): mixed
    {
        if (empty($categories)) {
            return $this->builder;
        }

         $this->builder->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('article_category.category_id', $categories);
        });

        return $this->builder;
    }

    /**
     * @param string $search
     * @return mixed
     */
    public function search(string $search): mixed
    {
        return $this->builder->where('articles.title', 'like', "%$search%");
    }

}
