<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public function stories()
    {
        return $this->belongsToMany(Story::class);
    }
}
