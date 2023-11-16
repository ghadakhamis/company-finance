<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Models\Transaction;

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

    public function update(Array $data, Transaction $transaction)
    {
        $this->repository->update($data, $transaction->id);
    }
}
