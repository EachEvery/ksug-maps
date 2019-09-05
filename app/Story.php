<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guraded = ['id'];
    protected $table = 'stories';
    protected $with = ['place'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
