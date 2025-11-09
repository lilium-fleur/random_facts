<?php

namespace App\Http\Controllers\Api\Fact;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\FactLikeResource;
use App\Services\Common\ResponseService;
use App\Services\Fact\FactLikeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class FactLikeController extends Controller
{

    public function __construct(
        private readonly FactLikeService $factLikeService
    )
    {
    }

    /**
     * @param int $factId
     * @return JsonResponse
     */
    public function store(int $factId): JsonResponse
    {
        try {
            $factLike = $this->factLikeService->like($factId);
            return ResponseService::success(FactLikeResource::make($factLike));
        } catch (NotFoundException $e) {
            return ResponseService::notFound($e->getMessage());
        }
    }


    /**
     * @param int $factId
     * @param int $likeId
     * @return JsonResponse
     */
    public function destroy(int $factId, int $likeId): JsonResponse
    {
        try {
            $this->factLikeService->unlike($factId, $likeId);
            return ResponseService::success();
        } catch (NotFoundException $e) {
            return ResponseService::notFound($e->getMessage());
        } catch (AuthorizationException $e) {
            return ResponseService::unauthorized($e->getMessage());
        } catch (Throwable $e) {
            return ResponseService::fromException($e);
        }
    }
}
