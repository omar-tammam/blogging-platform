<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleViewer extends Model
{
    use HasFactory;


    protected $fillable = [
        'article_id',
        'viewer_ip',
    ];

}
