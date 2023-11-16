<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class LoginController extends Controller
{
    private $service;

    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $resource = $this->service->loginUser($request->validated());
        return response()->json(['message' => trans('auth.login.success'), 'data' => $resource], Response::HTTP_OK);
    }
}
