<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatus;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'due_on', 'status', 'amount','vat', 'is_vat_inclusive'];

    protected $casts = [
        'due_on' => 'datetime',
        'status' => TransactionStatus::class,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            $transaction->status = $transaction->due_on->gt(Carbon::now())? TransactionStatus::OUTSTANDING : TransactionStatus::OVERDUE;
        });
    }
}
