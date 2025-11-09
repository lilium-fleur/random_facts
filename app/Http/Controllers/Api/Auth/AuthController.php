<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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

    public function verifyEmail($id, $hash): \Illuminate\Http\JsonResponse
    {
        try {
            $message = $this->service->verifyEmail($id, $hash);
            return response()->json([
                'message' => $message,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function resendVerificationEmail($id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $message = $this->service->resendVerificationEmail($id);
            return response()->json([
                'message' => $message,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
