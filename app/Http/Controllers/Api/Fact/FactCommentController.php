<?php

namespace App\Http\Controllers\Api\Fact;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fact\FactComment\FactCommentStoreRequest;
use App\Http\Requests\Fact\FactComment\FactCommentUpdateRequest;
use App\Http\Resources\FactCommentResource;
use App\Services\Common\ResponseService;
use App\Services\Fact\FactCommentService;
use Illuminate\Http\JsonResponse;

class FactCommentController extends Controller
{
    public function __construct(
        private readonly FactCommentService $factCommentService
    )
    {
    }


    /**
     * @param int $factId
     * @return JsonResponse
     */
    public function index(int $factId): JsonResponse
    {
        $comments = $this->factCommentService->getAll($factId);

        return ResponseService::success(FactCommentResource::collection($comments)->response()->getData());
    }

    /**
     * @param FactCommentStoreRequest $request
     * @param int $factId
     * @return JsonResponse
     */
    public function store(FactCommentStoreRequest $request, int $factId): JsonResponse
    {
        $data = $request->validated();

        try {
            $factComment = $this->factCommentService->createComment($data, $factId);

            return ResponseService::success(FactCommentResource::make($factComment));
        } catch (NotFoundException $e) {
            return ResponseService::notFound($e->getMessage());
        }
    }

    /**
     * @param FactCommentUpdateRequest $request
     * @param int $factId
     * @param int $commentId
     * @return JsonResponse
     */
    public function update(FactCommentUpdateRequest $request, int $factId, int $commentId): JsonResponse
    {
        $data = $request->validated();

        try {
            $factComment = $this->factCommentService->updateComment($data, $factId, $commentId);

            return ResponseService::success(FactCommentResource::make($factComment));
        } catch (NotFoundException $e) {
            return ResponseService::notFound($e->getMessage());
        }
    }

    /**
     * @param int $factId
     * @param int $commentId
     * @return JsonResponse
     */
    public function destroy(int $factId, int $commentId): JsonResponse
    {
        try {
            $isDeleted = $this->factCommentService->deleteComment($factId, $commentId);

            return $isDeleted ? ResponseService::success() : ResponseService::unSuccess();
        } catch (NotFoundException $e) {
            return ResponseService::notFound($e->getMessage());
        }

    }
}
