<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;

class CustomDirections extends Model
{
    protected $guarded = [];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
