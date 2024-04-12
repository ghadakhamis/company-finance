<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Http\Filters\TransactionFilter;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransactionService extends BaseService
{
    public function __construct(TransactionRepository $repository)
    {
        $this->setRepository($repository);
    }

    /**
     * Summary of create
     * @param array $data
     * @return Model
     */
    public function create(Array $data): Model
    {
        return $this->repository->create($data);
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

    public function statisticsReport(TransactionFilter $filter)
    {
        return $this->repository->statisticsReport($filter);
    }
}
