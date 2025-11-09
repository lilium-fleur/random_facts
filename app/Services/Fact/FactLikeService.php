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
    ];

    public function like(int $factId): FactLike
    {
        $fact = Fact::find($factId);

        if (!$fact) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

//        $userId = Auth::id();
        $userId = 1;

        $likeAlreadyExists = FactLike::where('fact_id', $factId)->where('user_id', $userId)->first();

        if ($likeAlreadyExists !== null) return $likeAlreadyExists;

        return FactLike::create([
            'fact_id' => $factId,
            'user_id' => $userId,
        ]);
    }

    public function unlike(int $factId): void
    {
//        $userId = Auth::id();
        $userId = 1;

        $factLike = FactLike::where('fact_id', $factId)->where('user_id', $userId)->first();
        if (!$factLike) throw new NotFoundException(self::ERROR_MESSAGES['factLikeNotFound']);

        $factLike->delete();
    }
}
