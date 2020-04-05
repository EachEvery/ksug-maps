<?php

namespace KSUGMap\Queries;

use KSUGMap\Tour;

class ToursWithPlaces
{
    public function query()
    {
        return Tour::with('stories', 'stories.place')->where('published', 1);
    }

    public function get()
    {
        return $this->query()->get();
    }
}
