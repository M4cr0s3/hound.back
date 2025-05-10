<?php

namespace App\Modules\Comment\Controller;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;

final readonly class CommentController
{
    public function destroy(Comment $comment): JsonResponse
    {
        \Gate::authorize('delete', $comment);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
