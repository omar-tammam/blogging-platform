<?php

namespace App\Http\Controllers\Admin\Article;

use App\Enum\HttpStatusCodeEnum;
use App\Enum\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\Article\ArticleFilter;
use App\Http\Filters\Article\ArticleViewerFilter;
use App\Http\Requests\Article\AddEditArticleRequest;
use App\Http\Resources\Admin\Article\ArticleDetailsResource;
use App\Http\Resources\Admin\Article\ArticleResource;
use App\Http\Resources\Admin\Article\ArticleViewerResource;
use App\Models\User;
use App\Services\Article\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ArticleAdminController extends Controller
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
        return $this->response(new ArticleDetailsResource($resource), HttpStatusCodeEnum::OK);
    }


    /**
     * @param AddEditArticleRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(AddEditArticleRequest $request): JsonResponse
    {
        $user = User::first() ?? User::factory()->create(); //temp code
        $request->merge(['created_by' => $user->id]); //temp code
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
        $user = User::first() ?? User::factory()->create();
        $request->merge(['created_by' => $user->id]); //temp code
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


    /**
     * @param Request $request
     * @param ArticleViewerFilter $filter
     * @return mixed
     */
    public function showViewers(Request $request, ArticleViewerFilter $filter): mixed
    {
        $paginator = $this->service->articleViewers(
            $request->get('page', PaginationEnum::PAGE),
            $request->get('perPage', PaginationEnum::LIMIT),
            $filter);

        return $this->response($this->formatPaginationData(ArticleViewerResource::collection($paginator), $paginator), HttpStatusCodeEnum::OK);
    }

}
