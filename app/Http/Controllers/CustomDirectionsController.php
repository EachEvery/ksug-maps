<?php

namespace KSUGMap\Http\Controllers;

use KSUGMap\CustomDirections;


class CustomDirectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        return CustomDirections::create(
            request()->input('custom_directions')
        );
    }

    public function update(CustomDirections $directions)
    {
        $directions->update(request()->input('custom_directions'));

        return $directions;
    }

    public function destroy(CustomDirections $directions)
    {
        $directions->delete();

        return 'Destroyed';
    }
}
