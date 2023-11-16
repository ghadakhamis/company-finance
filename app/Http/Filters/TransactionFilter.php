<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TransactionFilter extends Filter
{
    public $filters = ['user_id', 'sort'];
    public $fields = ['created_at'];

    /**
     * @param string $userId
     */
    public function user_id(string $userId)
    {
        $this->builder->where('user_id', $userId);
    }
}