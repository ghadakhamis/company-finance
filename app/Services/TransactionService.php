<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService extends BaseService
{
    public function __construct(TransactionRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create($data)
    {
        $this->repository->create($data);
    }
}
