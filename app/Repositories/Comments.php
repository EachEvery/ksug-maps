<?php

namespace KSUGMap\Repositories;

use KSUGMap\Comment;

class Comments
{
    public function __construct(Places $places)
    {
        $this->places = $places;
    }

    public function create($fillable)
    {
        return Comment::create($fillable);
    }

    public function forPlace($place)
    {
        return $place->comments()->whereApproved()->get();
    }
}
