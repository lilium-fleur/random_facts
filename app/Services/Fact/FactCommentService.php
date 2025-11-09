<?php

namespace App\Services\Fact;

use App\Exceptions\NotFoundException;
use App\Models\Fact;
use App\Models\FactComment;
use Illuminate\Pagination\LengthAwarePaginator;

class FactCommentService
{
    private const ERROR_MESSAGES = [
        'factNotFound' => 'Fact not found',
        'factCommentNotFound' => 'Fact\'s comment not found'
    ];

    /**
     * @param int $factId
     * @return LengthAwarePaginator
     */
    public function getAll(int $factId): LengthAwarePaginator
    {
        return FactComment::where('fact_id', $factId)
            ->latest()
            ->paginate(config('fact_comments.pagination.per_page'));
    }


    /**
     * @param array $data
     * @param int $factId
     * @return FactComment
     * @throws NotFoundException
     */
    public function createComment(array $data, int $factId): FactComment
    {
        $fact = Fact::find($factId);

        if ($fact === null) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

//        $userId = Auth::id();
        $userId = 1;
        return FactComment::create([
            'user_id' => $userId,
            'fact_id' => $fact->id,
            'content' => $data['content']
        ]);
    }

    /**
     * @param array $data
     * @param int $factId
     * @param int $commentId
     * @return FactComment
     * @throws NotFoundException
     */
    public function updateComment(array $data, int $factId, int $commentId): FactComment
    {
        $factComment = FactComment::find($commentId);

        if ($factComment === null) throw new NotFoundException(self::ERROR_MESSAGES['factCommentNotFound']);
        if ($factComment->fact_id !== $factId) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

        $factComment->update(['content' => $data['content']]);

        return $factComment;
    }

    /**
     * @param int $factId
     * @param int $commentId
     * @return bool
     * @throws NotFoundException
     */
    public function deleteComment(int $factId, int $commentId): bool
    {
        $factComment = FactComment::find($commentId);

        if ($factComment === null) throw new NotFoundException(self::ERROR_MESSAGES['factCommentNotFound']);
        if ($factComment->fact_id !== $factId) throw new NotFoundException(self::ERROR_MESSAGES['factNotFound']);

        return $factComment->delete();
    }
}
