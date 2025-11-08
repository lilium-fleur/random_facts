<?php

namespace App\Http\Controllers\Api\Fact;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fact\FactComment\FactCommentStoreRequest;
use App\Http\Requests\Fact\FactComment\FactCommentUpdateRequest;
use App\Http\Resources\FactCommentResource;
use App\Models\FactComment;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        return 111;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FactCommentStoreRequest $request, int $factId): JsonResponse
    {
        $data = $request->validated();

        try {
            $factComment = $this->factCommentService->createComment($data, $factId);

            return response()->json([
                'success' => true,
                'result' => FactCommentResource::make($factComment),
            ]);
        } catch (NotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FactCommentUpdateRequest $request, int $factId, int $commentId)
    {
        $data = $request->validated();

        try {
            $factComment = $this->factCommentService->updateComment($data, $factId, $commentId);
            return response()->json([
                'success' => true,
                'result' => FactCommentResource::make($factComment),
            ]);
        } catch (NotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $factId, int $commentId): JsonResponse
    {
        try {
            $isDeleted = $this->factCommentService->deleteComment($factId, $commentId);

            return response()->json([
                'success' => $isDeleted,
            ]);
        } catch (NotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }
}
