<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $guarded = [];


    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function getHasPhotoAttribute()
    {
        return filled($this->photo);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($place) {
            $place->slug = str_slug($place->name);
        });
    }
}
