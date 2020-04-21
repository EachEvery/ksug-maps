<?php

namespace KSUGMap\Http\Controllers;

class TourStoriesController
{
    public function index($tour)
    {
        return $tour->stories;
    }
}
