<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Lang;

abstract class Filter
{
    public $orders = ['asc', 'desc'];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @param Request $request
     * */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $field => $value) {
            $method = $field;
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    protected function getFilters(): array
    {
        return array_filter($this->request->only($this->filters));
    }

    /**
     * Sort the services by the given order and field.
     *
     * @param array $value
     */
    public function sort(string $value)
    {
        $sortData = explode(',', $value);
        $field = in_array($sortData[0], $this->fields)? $sortData[0] : 'created_at';
        $order = isset($sortData[1]) && in_array($sortData[1], $this->orders)? $sortData[1] : 'DESC';
        $this->builder->orderBy($field, $order);
    }
}
