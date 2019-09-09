<?php

namespace KSUGMap\Repositories;

use KSUGMap\Comment;

class Comments
{
    public function __construct(Stories $stories)
    {
        $this->stories = $stories;
    }

    public function create($fillable)
    {
        return Comment::create($fillable);
    }

    public function forStory($id)
    {
        return $this->stories->findOrFail($id)->comments()->whereApproved()->get();
    }
}
