<?php

namespace App\Http\Controllers\Article;

use App\Enum\HttpStatusCodeEnum;
use App\Enum\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\Article\ArticleFilter;
use App\Http\Requests\Article\AddEditArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Services\Article\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ArticleController extends Controller
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
            $filter, with: ['categories'], withCount: ['viewers']);

        return $this->response($this->formatPaginationData(ArticleResource::collection($paginator), $paginator), HttpStatusCodeEnum::OK);
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws Throwable
     */
    public function show(Request $request): mixed
    {
        $resource = $this->service->firstOrFailBy(['id' => $request->id] , withCount: ['viewers']);
        return $this->response(new ArticleResource($resource), HttpStatusCodeEnum::OK);
    }


    /**
     * @param AddEditArticleRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(AddEditArticleRequest $request): JsonResponse
    {
        $request->merge(['created_by' => 1]); //temp code
        $resource = $this->service->add($request->all());
        return $this->response(new ArticleResource($resource), HttpStatusCodeEnum::CREATED);
    }


    /**
     * @param AddEditArticleRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|Throwable
     */
    public function update(AddEditArticleRequest $request): JsonResponse
    {
        $request->merge(['created_by' => 1]); //temp code
        $resource = $this->service->update($request->id, $request->all());
        return $this->response(new ArticleResource($resource), HttpStatusCodeEnum::OK);
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws Throwable
     */
    public function destroy(Request $request): mixed
    {
        $this->service->delete([$request->id]);
        return $this->response([], HttpStatusCodeEnum::OK, trans('common.resource_deleted_successfully'));
    }


}
