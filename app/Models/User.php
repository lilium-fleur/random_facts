<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, MustVerifyEmail, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        Log::info('sendEmailVerificationNotification() ВЫЗВАН для пользователя ID: ' . $this->id);
        // Стандартная отправка
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);

        // ЛОКАЛЬНО — выводим ссылку в лог
        if (app()->environment('local')) {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $this->id, 'hash' => sha1($this->getEmailForVerification())]
            );

            Log::info('EMAIL VERIFICATION LINK (local):');
            Log::info($verificationUrl);
            Log::info('---'); // разделитель
        }
    }

    public function likes(): HasMany
    {
        return $this->hasMany(FactLike::class);
    }
}
