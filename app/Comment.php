<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];
    public $appends = ['frontend_date'];
    protected $dates = ['approved_at'];

    public function story()
    {
        return $this->belongsTo(Story::class);
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
