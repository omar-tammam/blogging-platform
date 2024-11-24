<?php

namespace App\Http\Resources\Admin\Article;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ArticleViewerResource extends JsonResource
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
            'logId' => $this->id,
            'viewerIp' => $this->viewer_ip,
            'articleId' => $this->article_id,
            'createdAt' => $this->created_at,
        ];
    }
}
