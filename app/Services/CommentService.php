<?php

namespace App\Services;

use App\Models\Comment;

class CommentService extends Service
{

    protected function getModelClass(): string
    {
        return Comment::class;
    }
}
