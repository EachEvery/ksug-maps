<?php

namespace KSUGMap\Repositories;

class Comments
{
    public function __construct(Stories $stories)
    {
        $this->stories = $stories;
    }

    public function forStory($id)
    {
        return $this->stories->findOrFail($id)->comments()->whereApproved()->get();
    }
}
