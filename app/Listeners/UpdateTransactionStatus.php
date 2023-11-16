<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Jobs\UpdateTransactionStatus as UpdateTransactionStatusJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class UpdateTransactionStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        $transaction = $event->transaction;

        if ($transaction->due_on->gt(now())) {
            UpdateTransactionStatusJob::dispatch($transaction->id)
                ->delay($transaction->due_on);
        }
    }
}
