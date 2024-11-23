<?php

namespace App\Http\Controllers\Category;

use App\Enum\HttpStatusCodeEnum;
use App\Enum\PaginationEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\Category\CategoryFilter;
use App\Http\Requests\Category\AddEditCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }


    /**
     * @param Request $request
     * @param CategoryFilter $filter
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, CategoryFilter $filter): mixed
    {
        $paginator = $this->service->paginate(
            $request->get('page', PaginationEnum::PAGE),
            $request->get('perPage', PaginationEnum::LIMIT),
            $filter);

        return $this->response($this->formatPaginationData(CategoryResource::collection($paginator), $paginator), HttpStatusCodeEnum::OK);
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws Throwable
     */
    public function show(Request $request): mixed
    {
        $resource = $this->service->find($request->id);
        return $this->response(new CategoryResource($resource), HttpStatusCodeEnum::OK);
    }


    /**
     * @param AddEditCategoryRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|Throwable
     */
    public function store(AddEditCategoryRequest $request): JsonResponse
    {
        $resource = $this->service->add($request->all());
        return $this->response(new CategoryResource($resource), HttpStatusCodeEnum::CREATED);
    }


    /**
     * @param AddEditCategoryRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|Throwable
     */
    public function update(AddEditCategoryRequest $request): JsonResponse
    {
        $resource = $this->service->update($request->id, $request->all());
        return $this->response(new CategoryResource($resource), HttpStatusCodeEnum::OK);
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
