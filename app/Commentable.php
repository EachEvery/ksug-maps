<?php

namespace KSUGMap;

trait Commentable
{
    public function approved_comments()
    {
        return $this->comments()->whereNotNull('approved_at');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
