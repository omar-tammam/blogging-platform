<?php

namespace App\Models\Category;

use App\Models\Article\Article;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'slug',
    ];


    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category', 'category_id', 'article_id');
    }


}
