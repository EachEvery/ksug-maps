<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tour extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $with = ['photos', 'stories', 'custom_directions'];
    public $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tour_cover')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->singleFile();
    }

    public function custom_directions()
    {
        return $this->hasMany(CustomDirections::class);
    }

    public function photos()
    {
        return $this->media()->where('collection_name', 'tour_cover');
    }

    public function stories()
    {
        return $this->belongsToMany(Story::class)
            ->withPivot(['id', 'sort_order', 'custom_directions'])
            ->orderBy('story_tour.sort_order');
    }

    public function getPublicUrlAttribute()
    {
        return url('tours/' . $this->slug);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($tour) {
            $tour->slug = str_slug($tour->name);
        });
    }
}
