<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $guarded = [];

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}
