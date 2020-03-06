<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public function stories()
    {
        return $this->belongsToMany(Story::class);
    }

    public function getPublicUrlAttribute()
    {
        return url('tours/'.$this->slug);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($tour) {
            $tour->slug = str_slug($tour->name);
        });
    }
}
