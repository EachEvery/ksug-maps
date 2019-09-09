<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Repositories\Comments;
use Illuminate\Http\Request;

class StoryCommentsController
{
    public function __construct(Comments $comments)
    {
        $this->comments = $comments;
    }

    public function index(Request $req)
    {
        return $this->comments->forStory($req->route('story_id'));
    }

    public function store(Request $req)
    {
        return $this->comments->create(
            array_merge($req->input('comment'), [
                'story_id' => $req->route('story_id'),
            ])
        );
    }
}
