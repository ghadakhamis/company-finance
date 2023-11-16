<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Services\PaymentService;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PaymentController extends Controller
{
    private $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function store(StorePaymentRequest $request, Transaction $transaction): JsonResponse
    {
        $resource = $this->service->create($request->validated(), $transaction);
        return response()->json(['message' => trans('auth.create.success'), 'data' => $resource], Response::HTTP_OK);
    }
}
