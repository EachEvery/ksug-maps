<?php

namespace KSUGMap\Repositories;

use KSUGMap\Place;

class Places
{
    public function matchingSlug($slug)
    {
        return Place::whereSlug($slug)->first();
    }

    public function findOrFail($id)
    {
        return Place::findOrFail($id);
    }

    public function all()
    {
        return Place::all();
    }
}
