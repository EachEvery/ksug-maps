<?php

namespace KSUGMap\Comment;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['approved_at'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function scopeWhereApproved($q)
    {
        return $q->whereNotNull('approved_at');
    }
}
