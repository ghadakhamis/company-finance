<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TransactionService;
use App\Enums\TransactionStatus;

class UpdateTransactionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = app(TransactionService::class);
        $transaction = $service->findBy('id', $this->id);

        if ($transaction && $transaction->isStatus(TransactionStatus::OUTSTANDING)) {
            $service->update(['status' => TransactionStatus::OVERDUE], $transaction);
        }
    }
}
