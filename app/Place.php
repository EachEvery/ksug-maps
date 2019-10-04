<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use KSUGMap\Contracts\MapsToSearchResult;
use Laravel\Scout\Searchable;

class Place extends Model implements MapsToSearchResult
{
    use Searchable;

    protected $appends = ['admin_url', 'public_url'];
    public $with = ['approved_comments'];
    protected $guarded = [];

    public function getAdminUrlAttribute()
    {
        return url(sprintf('/admin/resources/places/%s/edit', $this->id));
    }

    public function getPublicUrlAttribute()
    {
        return url(sprintf('/places/%s/preview', $this->slug));
    }

    public function getPhotoAttribute($val)
    {
        info($val);

        return filled($val) ? str_replace(['http:', 'https:'], '', $val) : null;
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approved_comments()
    {
        return $this->comments()->whereNotNull('approved_at');
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
