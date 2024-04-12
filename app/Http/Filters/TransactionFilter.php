<?php

namespace App\Http\Filters;

use Illuminate\Http\Request;

class TransactionFilter extends Filter
{
     /**
     * @param Request $request
     * */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->filters = ['user_id', 'start_date', 'end_date', 'sort'];
        $this->fields  = ['created_at', 'year', 'month'];
    }

    /**
     * @param string $userId
     */
    public function user_id(string $userId)
    {
        $this->builder->where('user_id', $userId);
    }

    /**
     * @param string $date
     */
    public function start_date(string $date)
    {
        $this->builder->whereDate('due_on', '>=', $date);
    }

    /**
     * @param string $date
     */
    public function end_date(string $date)
    {
        $this->builder->whereDate('due_on', '<=', $date);
    }
}