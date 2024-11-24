<?php

namespace App\Http\Controllers\User\Article;

use App\Enum\HttpStatusCodeEnum;
use App\Enum\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\Article\ArticleFilter;
use App\Http\Resources\User\Article\ArticleDetailsResource;
use App\Http\Resources\User\Article\ArticleResource;
use App\Models\Article\Article;
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
     * @param ArticleFilter $filter
     * @return mixed
     */
    /**
     * @param Request $request
     * @param ArticleFilter $filter
     * @return mixed
     */
    public function random(Request $request, ArticleFilter $filter): mixed
    {
        $page = $request->get('page', PaginationEnum::PAGE);
        $perPage = $request->get('perPage', PaginationEnum::LIMIT);

        $pagination = $this->service->randomPaginate(
            $page,
            $perPage,
            $filter,
            with: ['categories'],randomizedOrder: $request->get('randomizedOrder', null));

        $paginator = $pagination['paginator'];
        $randomizedOrder = $pagination['randomizedOrder'];

        return $this->response(
            $this->formatRandomPaginationData(
                ArticleResource::collection($paginator),
                count(explode(',', $randomizedOrder)),
                $perPage,
                $page
            ) + ['randomizedOrder' => $randomizedOrder], // Include the randomized order for the next pagination
            HttpStatusCodeEnum::OK
        );
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
