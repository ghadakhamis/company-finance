<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Http\Filters\TransactionFilter;
use App\Models\Transaction;
use Auth;

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

    public function filter(TransactionFilter $filter)
    {
        $this->repository->setRelations(['user', 'payments']);
        $search = $this->repository->getModelData($filter, true);
        return $search;
    }

    public function userFilter(TransactionFilter $filter)
    {
        $this->repository->setScopes(['userId' => Auth::user()->id]);
        return $this->filter($filter);
    }
}
