<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use KSUGMap\Contracts\MapsToSearchResult;

class Story extends Model implements MapsToSearchResult
{
    use Searchable;

    protected $guraded = ['id'];
    protected $table = 'stories';
    protected $with = ['place', 'approved_comments'];

    public function toSearchResult(): object
    {
        return (object) [
            'title' => sprintf('%s · %s', $this->subject, $this->place->name),
            'subtitle' => sprintf('<i>%s</i> - %s', $this->day, str_limit($this->content)),
            'path' => sprintf('/stories/%s', $this->id),
            'role' => $this->role,
        ];
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approved_comments()
    {
        return $this->comments()->whereNotNull('approved_at');
    }
}
