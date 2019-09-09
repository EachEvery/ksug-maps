<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guraded = ['id'];
    protected $table = 'stories';
    protected $with = ['place', 'approved_comments'];

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
