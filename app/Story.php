<?php

namespace KSUGMap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use KSUGMap\Contracts\MapsToSearchResult;
use Laravel\Scout\Searchable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Story extends Model implements MapsToSearchResult, Sortable
{
    use Searchable;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => false,
        'sort_on_pivot' => true,
    ];

    protected $guraded = ['id'];
    protected $table = 'stories';
    protected $with = ['place'];
    protected $appends = ['admin_url', 'public_url'];

    public function getRoleAttribute($val)
    {
        return Str::title(trim($val));
    }

    public function getAdminUrlAttribute()
    {
        return url(sprintf('/admin/resources/stories/%s/edit', $this->id));
    }

    public function getPublicUrlAttribute()
    {
        return url(sprintf('/stories/%s', $this->id));
    }

    public function toSearchResult(): object
    {
        return (object) [
            'title' => $this->title,
            'subtitle' => sprintf('<i>%s</i> - %s', $this->day, Str::limit($this->content)),
            'path' => sprintf('/stories/%s', $this->id),
            'role' => $this->role,
        ];
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class);
    }
}
