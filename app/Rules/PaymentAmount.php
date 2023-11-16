<?php

namespace App\Rules;

use App\Models\transaction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PaymentAmount implements ValidationRule
{
    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $paidAmount   = $this->transaction->paid_amount;
        $totalAmount  = $this->transaction->total_amount;
        $unpaidAmount = $totalAmount - $paidAmount;

        if ($value > $unpaidAmount) {
            $fail(trans('validation.lte.numeric', ['attribute' => 'amount', 'value' => $unpaidAmount]));
        }
    }
}
