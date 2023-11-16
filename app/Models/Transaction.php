<?php

namespace App\Models;

use App\Events\TransactionCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatus;
use App\Traits\FilterTrait;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, FilterTrait;

    protected $fillable = ['user_id', 'due_on', 'status', 'amount','vat', 'is_vat_inclusive'];

    protected $casts = [
        'due_on' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
    ];

    public function isStatus($status)
    {
        return $this->status == $status;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Interact with the transaction's total_amount.
     */
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_vat_inclusive? $this->amount : ($this->amount * (1 + $this->vat/100)),
        );
    }

    /**
     * Interact with the transaction's paid_amount.
     */
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->payments()->sum('amount')
        );
    }

    public function scopeUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            $transaction->status = $transaction->due_on->gt(Carbon::now())?
                TransactionStatus::OUTSTANDING : TransactionStatus::OVERDUE;
        });
    }
}
