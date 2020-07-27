<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Story;
use KSUGMap\Tour;

class TourStoriesController
{
    public function index(Tour $tour)
    {
        return $tour->stories;
    }

    public function update(Tour $tour, Story $story)
    {
        $tour->stories()->updateExistingPivot(
            $story->id,
            request('tour_story')
        );

        return $tour->stories()->findOrFail($story->id);
    }
}
