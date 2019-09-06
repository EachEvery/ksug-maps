<?php

namespace KSUGMap\Repositories;

use KSUGMap\Story;

class Stories
{
    public function all()
    {
        return Story::all();
    }

    public function findOrFail($id)
    {
        return Story::findOrFail($id);
    }
}
