<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\Contracts\MapsToSearchResult;
use KSUGMap\Story;

class SearchController extends Controller
{
    protected $modelsToSearch = [
        Story::class,
    ];

    public function __invoke()
    {
        if (empty(request('q', null))) {
            return collect();
        }

        return collect($this->modelsToSearch)
            ->flatMap(function ($class) {
                return $class::search(request('q'))->get();
            })
            ->map(function (MapsToSearchResult $item) {
                return $item->toSearchResult();
            })->sortBy('title')->values();
    }
}
