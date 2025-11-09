<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register($data): array
    {
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('token')->plainTextToken;

        $user->sendEmailVerificationNotification();

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * @throws ValidationException
     */
    public function login($data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        request()->user()->tokens()->delete();
    }

    /**
     * @throws ValidationException
     */
    public function updateProfile($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['User not found.'],
            ]);
        }

        return $user->update($data);
    }

    /**
     * @throws ValidationException
     */
    public function changePassword($data): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();
        return $user;
    }


    /**
     * @throws AuthorizationException
     */
    public function verifyEmail($id, $hash): string
    {
        $user = User::find($id);

        if (!$user) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException('Verification link is invalid.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return 'Email verified successfully!';
    }

    public function resendVerificationEmail($id): string
    {
        $user = User::find($id);

        if (!$user) {
            throw new ModelNotFoundException('User not found.');
        }

        if ($user->hasVerifiedEmail()) {
            return 'Email verified already.';
        }

        $user->sendEmailVerificationNotification();
        return 'Email verification link sent on your inbox.';
    }
}
