<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Tappable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Tappable;

    public $appends = ['frontend_date', 'media_is_image', 'media_url', 'has_media', 'comment_media'];

    protected $dates = ['approved_at'];
    protected $guarded = [];

    public function getMediaIsImageAttribute()
    {
        return str_contains(optional($this->comment_media)->mime_type, 'image');
    }

    public function getCommentMediaAttribute()
    {
        $key = sprintf('comments:%s:%s:comment_media', $this->id, $this->updated_at);

        return Cache::rememberForever($key, function () {
            return $this->getMedia('comment_media')->first();
        });
    }

    public function getHasMediaAttribute()
    {
        return ! empty($this->comment_media);
    }

    public function getMediaUrlAttribute()
    {
        return optional($this->comment_media)->getFullUrl();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('comment_media')
            ->singleFile();
    }

    public function getIsApprovedAttribute()
    {
        return filled($this->approved_at);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function scopeWhereApproved($q)
    {
        return $q->whereNotNull('approved_at');
    }

    public function approve()
    {
        return tap($this)->update([
            'approved_at' => now(),
        ]);
    }

    public function getFrontendDateAttribute()
    {
        return $this->created_at->format('m.d.Y');
    }
}
