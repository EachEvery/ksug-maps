<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Tappable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Tappable;

    public $appends = ['frontend_date'];
    protected $dates = ['approved_at'];
    protected $guarded = [];

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
