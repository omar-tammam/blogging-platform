<?php

namespace App\Http\Resources\Admin\Article;

use App\Http\Resources\Admin\Category\CategoryResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'categories' => CategoryResource::collection($this->categories),
            'viewersCount' => $this->viewers_count,
        ];
    }
}
