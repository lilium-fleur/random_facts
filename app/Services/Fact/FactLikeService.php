<?php

namespace App\Services\Fact;

use App\Exceptions\NotFoundException;
use App\Models\Fact;
use App\Models\FactLike;
use Illuminate\Auth\Access\AuthorizationException;
use RuntimeException;

class FactLikeService
{
    private const ERROR_MESSAGES = [
        'factNotFound' => 'Fact not found',
        'factLikeNotFound' => 'Fact\'s like not found',
        'authPermissions' => 'You cannot modify this data'
    ];

    public function like(int $factId): FactLike
    {
        $fact = Fact::find($factId);

        if (!$fact) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

//        $userId = Auth::id();
        $userId = 1;

        return FactLike::create([
            'fact_id' => $factId,
            'user_id' => $userId,
        ]);
    }

    public function unlike(int $factId, int $likeId): void
    {
        $fact = Fact::find($factId);

        if (!$fact) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

        $factLike = FactLike::find($likeId);
        if (!$factLike) throw new NotFoundException(self::ERROR_MESSAGES['factLikeNotFound']);

//        $userId = Auth::id();
        $userId = 1;
        if ($factLike->user_id !== $userId) throw new AuthorizationException(self::ERROR_MESSAGES['authPermissions']);
        if ($factLike->fact_id !== $fact->id) throw new RuntimeException();

        $factLike->delete();
    }
}
