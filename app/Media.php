<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    public $appends = ['url'];
    public $touches = ['model'];

    public function getUrlAttribute()
    {
        return $this->getFullUrl();
    }

    protected static function boot()
    {
        parent::boot();
    }
}
