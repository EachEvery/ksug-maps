<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Repositories\Stories;

class StoryController extends Controller
{
    public function index(Stories $stories)
    {
        return $stories->all();
    }

    public function update($story)
    {
        $this->middleware('auth');

        $story->update(
            request()->input('story')
        );

        return $story->fresh();
    }
}
