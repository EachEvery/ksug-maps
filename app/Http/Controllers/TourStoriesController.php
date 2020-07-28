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
}
