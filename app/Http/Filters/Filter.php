<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        collect($this->filters())->each(function ($value, $filter) {
            if (method_exists($this, $filter)) {
                $this->{$filter}($value);
            }
        });

        return $this->builder;
    }


    /**
     * @return array
     */
    protected function filters(): array
    {
        return array_filter($this->request->all());
    }


}
