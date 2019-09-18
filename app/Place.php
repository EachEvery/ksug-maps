<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use KSUGMap\Contracts\MapsToSearchResult;
use Laravel\Scout\Searchable;

class Place extends Model implements MapsToSearchResult
{
    use Searchable;

    protected $appends = ['admin_url', 'public_url'];

    protected $guarded = [];

    public function getAdminUrlAttribute()
    {
        return url(sprintf('/admin/resources/places/%s/edit', $this->id));
    }

    public function getPublicUrlAttribute()
    {
        return url(sprintf('/places/%s/preview', $this->slug));
    }

    public function toSearchResult(): object
    {
        $storyCount = $this->stories()->count();

        return (object) [
            'title' => $this->name,
            'subtitle' => sprintf('%s %s', $storyCount, $storyCount > 1 ? 'Stories' : 'Story'),
            'path' => sprintf('/places/%s/preview', $this->slug),
        ];
    }

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
