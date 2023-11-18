<?php

namespace App\Repositories;

use App\Enums\TransactionStatus;
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
        // TODO calculate vat value
        return $this->model->selectRaw(
                'YEAR(due_on) AS year, MONTH(due_on) AS month,
                SUM(transactions.amount) AS total, SUM(payments.amount) AS paid,
                SUM(CASE WHEN transactions.status = '.TransactionStatus::OUTSTANDING.' THEN transactions.amount ELSE 0 END) - SUM(COALESCE(payments.amount, 0)) AS outstanding,
                SUM(CASE WHEN transactions.status = '.TransactionStatus::OVERDUE.' THEN transactions.amount ELSE 0 END) - SUM(COALESCE(payments.amount, 0)) AS overdue'
            )->leftJoin('payments', 'payments.transaction_id', 'transactions.id')
            ->filter($filter)
            ->groupBy('year','month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate();
    }
}