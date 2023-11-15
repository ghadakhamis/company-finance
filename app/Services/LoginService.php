<?php

namespace App\Services;

use Illuminate\Http\Response;
use Hash;

class LoginService extends BaseService
{
    public function loginAdmin($data)
    {
        $admin = app(AdminService::class)->findBy('email', $data['email']);

        if (!$admin || !$this->confirmPassword($data['password'], $admin->password)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, trans('auth.login.fail'));
        }

        $admin->token = $this->generateToken($admin);
        return $admin->getResource();
    }

    public function loginUser($data)
    {
        $user = app(UserService::class)->findBy('phone', $data['phone']);

        if (!$user || !$this->confirmPassword($data['password'], $user->password)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, trans('auth.login.fail'));
        }

        $user->token = $this->generateToken($user);
        return $user->getResource();
    }

    public function confirmPassword(string $input, string $password): bool
    {
        return Hash::check($input, $password);
    }

    public function generateToken($model)
    {
        return $model->createToken('auth_token')->plainTextToken;
    }
}
