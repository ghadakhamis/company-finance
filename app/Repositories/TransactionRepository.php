<?php

namespace App\Repositories;

use App\Http\Filters\TransactionFilter;
use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function model()
    {
        return Transaction::class;
    }

    public function statisticsReport(TransactionFilter $filter)
    {
        return $this->model->selectRaw(
                'YEAR(due_on) AS year, MONTH(due_on) AS month, sum(amount) AS total,
                (SELECT sum(amount) FROM payments WHERE payments.transaction_id = transactions.id AND deleted_at IS NULL) AS paid'
            )->filter($filter)
            ->groupBy('year','month', 'transactions.id')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate();
    }
}