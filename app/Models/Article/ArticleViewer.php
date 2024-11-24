<?php

namespace App\Models\Article;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleViewer extends Model
{
    use HasFactory, Filterable;


    protected $fillable = [
        'article_id',
        'viewer_ip',
    ];

}
