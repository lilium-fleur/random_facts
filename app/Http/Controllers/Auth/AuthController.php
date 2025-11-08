<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function register(RegisterRequest $request): AuthResource
    {
        $requestData = $request->validated();

        $responseData = $this->service->register($requestData);

        return new AuthResource($responseData['user'], $responseData['token']);
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse|AuthResource
    {
        $requestData = $request->validated();

        try {
            $responseData = $this->service->login($requestData);
            return new AuthResource($responseData['user'], $responseData['token']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error while logging in',
                'errors' => $e->errors(),
            ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
    }

    public function updateProfile(UpdateProfileRequest $request): UserResource|\Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        try {
            $user = $this->service->updateProfile($data);
            return new UserResource($user);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error while updating your profile',
                'errors' => $e->errors(),
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();

        try {
            $user = $this->service->changePassword($data);
            return new UserResource($user);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error while updating your password',
                'errors' => $e->errors(),
            ]);
        }
    }
}
