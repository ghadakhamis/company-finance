<?php

use App\Enums\TransactionStatus;

return [
    TransactionStatus::class => [
        TransactionStatus::OUTSTANDING => 'Outstanding',
        TransactionStatus::PAID        => 'Paid',
        TransactionStatus::OVERDUE     => 'Overdue',
    ],
];
