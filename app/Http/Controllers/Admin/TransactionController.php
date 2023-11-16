<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
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

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $resource = $this->service->create($request->validated());
        return response()->json(['message' => trans('auth.create.success'), 'data' => $resource], Response::HTTP_OK);
    }
}
