<?php

namespace App\Http\Controllers\User\Article;

use App\Enum\HttpStatusCodeEnum;
use App\Enum\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\Article\ArticleFilter;
use App\Http\Resources\User\Article\ArticleDetailsResource;
use App\Http\Resources\User\Article\ArticleResource;
use App\Services\Article\ArticleService;
use Illuminate\Http\Request;
use Throwable;

class ArticleUserController extends Controller
{
    /**
     * @var ArticleService
     */
    private ArticleService $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }


    /**
     * @param Request $request
     * @param ArticleFilter $filter
     * @return mixed
     */
    public function index(Request $request, ArticleFilter $filter): mixed
    {
        $paginator = $this->service->paginate(
            $request->get('page', PaginationEnum::PAGE),
            $request->get('perPage', PaginationEnum::LIMIT),
            $filter, with: ['categories']);

        return $this->response($this->formatPaginationData(ArticleResource::collection($paginator), $paginator), HttpStatusCodeEnum::OK);
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws Throwable
     */
    public function preview(Request $request): mixed
    {
        $resource = $this->service->preview($request->id, $request->ip());
        return $this->response(new ArticleDetailsResource($resource), HttpStatusCodeEnum::OK);
    }



}
