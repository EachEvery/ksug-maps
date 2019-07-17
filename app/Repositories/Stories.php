<?php

namespace KSUGMap\Repositories;

use KSUGMap\Story;

class Stories
{
    public function all()
    {
        return Story::all();
    }
}
