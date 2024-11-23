<?php

namespace App\Services;

use App\Http\Filters\Filter;
use App\Repositories\BaseRepository;
use Closure;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class BaseService
{
    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    /**
     * @var string
     */
    protected string $entity;

    /**
     * Create a new controller instance.
     * @param BaseRepository $BaseRepository
     */
    public function __construct(BaseRepository $BaseRepository)
    {
        $this->repository = $BaseRepository;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Throwable
     */
    public function find($id): mixed
    {
        return $this->applyTransaction(function () use ($id) {
            return $this->repository->find($id);
        });
    }


    /**
     * @param int $page
     * @param int $perPage
     * @param Filter $filter
     * @param array $columns
     * @param array $with
     * @param array $withCount
     * @return mixed
     */
    public function paginate(int $page, int $perPage, Filter $filter, array $columns = ["*"], array $with=[], array $withCount=[]): mixed
    {
        return $this->repository->paginate($page, $perPage, $filter, $columns, $with, $withCount);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Throwable
     */
    public function findOrFail(int $id): mixed
    {
        return $this->applyTransaction(function () use ($id) {
            return $this->repository->findOrFail($id);
        });
    }

    /**
     * @param array $filters
     * @param array $with
     * @param array $withCount
     * @param array $columns
     * @return mixed
     * @throws Throwable
     */
    public function firstOrFailBy(array $filters = [], array $with = [], array $withCount=[], array $columns = ['*']): mixed
    {
        return $this->applyTransaction(function () use ($filters, $with, $withCount,  $columns) {
            return $this->repository->firstOrFailBy($filters, $with, $withCount ,$columns);
        });
    }

    /**
     * @param array $filters
     * @param array $with
     * @param array $withCount
     * @param array $columns
     * @return mixed
     * @throws Throwable
     */
    public function firstBy(array $filters = [], array $with = [], array $withCount=[], array $columns = ['*']): mixed
    {
        return $this->applyTransaction(function () use ($filters, $with, $withCount,  $columns) {
            return $this->repository->firstBy($filters, $with, $withCount ,$columns);
        });
    }

    /**
     * @param Closure $callback
     * @return mixed
     * @throws Throwable
     */
    public function applyTransaction(Closure $callback): mixed
    {
        return $this->repository->applyTransaction($callback);
    }

    /**
     * @param array $filters
     * @param array $with
     * @param string $orderBy
     * @param string $direction
     * @param array $columns
     * @return mixed
     */
    public function findAll(array $filters = [], array $with = [], string $orderBy = 'created_at', string $direction = 'DESC', array $columns = ['*']): mixed
    {
        return $this->repository->findAll($filters, $with, $orderBy, $direction, $columns);
    }


    /**
     * @param string $column
     * @param array $ids
     * @param array $with
     * @return mixed
     */
    public function findAllByWhereIn(string $column, array $ids, array $with =[]): mixed
    {
        return $this->repository->findAllByWhereIn($column, $ids, $with);
    }
    /**
     * @param mixed $data
     * @return mixed
     * @throws Throwable
     */
    public function add(mixed $data): mixed
    {
        return $this->applyTransaction(function () use ($data) {
            return $this->repository->add($data);
        });
    }

    /**
     * @param array $search
     * @param array $create
     * @return mixed
     * @throws Throwable
     */
    public function firstOrCreate(array $search, array $create = []): mixed
    {
        return $this->applyTransaction(function () use ($search, $create) {
            return $this->repository->firstOrCreate($search, $create);
        });
    }


    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function insert(array $data): mixed
    {
        return $this->applyTransaction(function () use ($data) {
            return $this->repository->insert($data);
        });
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function insertOrIgnore(array $data): mixed
    {
        return $this->applyTransaction(function () use ($data) {
            return $this->repository->insertOrIgnore($data);
        });
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function insertGetId(array $data): mixed
    {
        return $this->applyTransaction(function () use ($data) {
            return $this->repository->insertGetId($data);
        });
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws EntityNotFoundException|Throwable
     */
    public function update(int $id, array $data): mixed
    {
        $this->findOrFail($id);
        return $this->applyTransaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }


    /**
     * @param array $filters
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    public function updateManyWhere(array $filters, array $data): mixed
    {
        return $this->applyTransaction(function () use ($filters, $data) {
            return $this->repository->updateManyWhere($filters, $data);
        });
    }

    /**
     * @param string $column
     * @param array $ids
     * @param array $attributes
     * @return mixed
     * @throws Throwable
     */
    public function updateWhereIn(string $column, array $ids, array $attributes): mixed
    {
        return $this->applyTransaction(function () use ($column, $ids, $attributes) {
            return $this->repository->updateWhereIn($column, $ids, $attributes);
        });
    }


    /**
     * @param array $ids
     * @return mixed
     * @throws Throwable
     */
    public function delete(array $ids): mixed
    {
        return $this->applyTransaction(function () use ($ids) {

            return $this->repository->delete($ids);
        });
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Throwable
     */
    public function deleteOne(int $id): mixed
    {
        return $this->applyTransaction(function () use ($id) {
            return $this->repository->deleteOne($id);
        });
    }


    /**
     * @param array $filters
     * @return mixed
     * @throws Throwable
     */
    public function deleteCollectionBy(array $filters): mixed{
        return $this->applyTransaction(function () use ($filters) {
            return $this->repository->deleteCollectionBy($filters);
        });
    }

    /**
     * @param string $column
     * @param array $where
     * @return mixed
     */
    public function max(string $column, array $where): mixed
    {
        return $this->repository->max($column, $where);
    }

    /**
     * @param string $column
     * @param array $where
     * @return mixed
     */
    public function min(string $column, array $where): mixed
    {
        return $this->repository->min($column, $where);
    }
}
