<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Repositories\Comments;
use Illuminate\Http\Request;
use KSUGMap\Repositories\Places;

class PlaceCommentsController
{
    public function __construct(Comments $comments, Places $places)
    {
        $this->comments = $comments;
        $this->places = $places;
    }

    public function index(Request $req)
    {
        $place = $this->places->matchingSlug(
            $req->route('place_slug')
        );

        return $this->comments->forPlace($place);
    }

    public function store(Request $req)
    {
        $place = $this->places->matchingSlug(
            $req->route('place_slug')
        );

        return $this->comments->create(
            array_merge($req->input('comment'), [
                'place_id' => $place->id,
            ])
        );
    }
}
