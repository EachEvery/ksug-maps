<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Queries\PublishedLearningResources;

class LearningResourceController
{
    public function index()
    {
        return (new PublishedLearningResources)->get();
    }
}
