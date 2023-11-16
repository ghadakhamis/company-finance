<?php

namespace App\Policies;

use App\Enums\TransactionStatus;
use App\Models\Admin;
use App\Models\Transaction;

class PaymentPolicy
{
    /**
     * Determine if the given transaction can be paid by the admin.
     */
    public function create(Admin $admin, Transaction $transaction): bool
    {
        return !$transaction->isStatus(TransactionStatus::PAID);
    }
}
