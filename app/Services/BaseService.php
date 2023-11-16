<?php

namespace App\Services;

use \Exception;
use \Bosnadev\Repositories\Eloquent\Repository;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected $repository;

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function findBy(string $column, string $value)
    {
        return $this->repository->findBy($column, $value);
    }
}