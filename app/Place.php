<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use KSUGMap\Contracts\MapsToSearchResult;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Place extends Model implements MapsToSearchResult, HasMedia
{
    use InteractsWithMedia;
    use Searchable;
    use Commentable;

    protected $appends = ['admin_url', 'public_url'];

    public $with = ['photos', 'approved_comments'];

    protected $guarded = [];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('place_photos');
    }

    public function photos()
    {
        return $this->media()->where('collection_name', 'place_photos');
    }

    public function getAdminUrlAttribute()
    {
        return url(sprintf('/admin/resources/places/%s/edit', $this->id));
    }

    public function getPublicUrlAttribute()
    {
        return url(sprintf('/places/%s/preview', $this->slug));
    }

    public function getEmbedUrlAttribute()
    {
        return url(sprintf('/places/%s/embed', $this->slug));
    }

    public function getPhotoAttribute($val)
    {
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

    public function getEmbedCodeAttribute()
    {
        $q = http_build_query([
            'mousewheel' => 'false',
            'access_token' => env('MAPBOX_PK'),
            'fresh' => 'true',
            'title' => 'view',
        ]);

        $hash = sprintf('%s/%s/%s/0/0', 16.88, $this->lat, $this->long);
        $iframeSrc = sprintf("https://api.mapbox.com/styles/v1/natehobi/ck8lzpm8m10co1jp7622bt57x.html?%s#%s", $q, $hash);

        return view('placeEmbedCode', [
            'place' => $this,
            'iframeSrc' => $iframeSrc
        ])->render();
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
            $place->slug = Str::slug($place->name);
        });
    }
}
