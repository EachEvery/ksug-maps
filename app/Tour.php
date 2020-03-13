<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public function stories()
    {
        return $this->belongsToMany(Story::class)
            ->withPivot(['id', 'sort_order'])
            ->orderBy('story_tour.sort_order');
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
