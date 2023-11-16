<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Filters\TransactionFilter;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class TransactionController extends Controller
{
    private $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index(TransactionFilter $filter, SearchTransactionRequest $request)
    {
        $result = $this->service->userFilter($filter);
        return new TransactionCollection($result);
    }
}
