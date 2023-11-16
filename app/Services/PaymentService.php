<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Repositories\PaymentRepository;
use App\Models\Payment;

class PaymentService extends BaseService
{
    public function __construct(PaymentRepository $repository)
    {
        $this->setRepository($repository);
    }

    public function create($data, $transaction)
    {
        $transaction->payments()->create($data);

        if ($transaction->total_amount == $transaction->paid_amount) {
            app(TransactionService::class)->update(
                ['status' => TransactionStatus::PAID], $transaction
            );
        }
    }
}
