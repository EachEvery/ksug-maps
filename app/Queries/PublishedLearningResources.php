<?php

namespace KSUGMap\Queries;

use KSUGMap\LearningResource;

class PublishedLearningResources
{
    public function get()
    {
        return LearningResource::where('published', 1)->orderBy('label', 'ASC')->get();
    }
}
