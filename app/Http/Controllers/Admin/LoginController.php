<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAdminRequest;
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

    public function login(LoginAdminRequest $request): JsonResponse
    {
        $resource = $this->service->loginAdmin($request->validated());
        return response()->json(['message' => trans('auth.login.success'), 'data' => $resource], Response::HTTP_OK);
    }
}
