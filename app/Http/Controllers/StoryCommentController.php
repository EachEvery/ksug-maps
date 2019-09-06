<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Repositories\Comments;
use TANIOS\Airtable\Request;

class StroyCommentController
{
    public function __construct(Comments $comments)
    {
        $this->comments = $comments;
    }

    public function index(Request $req)
    {
        return $this->comments->forStory($req->path('story_id'));
    }

    public function store(Request $req)
    {
        return $this->comments->create($req->input('comment'));
    }
}
