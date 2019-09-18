<?php

namespace KSUGMap\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

class Place extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'KSUGMap\Place';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            Text::make('Public Url')->hideFromIndex(),
            Text::make('Photo')->hideFromIndex(),
            Text::make('Alt Text')->hideFromIndex(),
            Text::make('Photo Preview', function () {
                $isList = 'nova-api/places' === request()->path();
                $dimensions = $isList ? 'width: 100px; height 50px;' : 'width: 612px; height 256px;';

                return sprintf(
                    '<img src="%s" class=" my-2 shadow-lg  transition bg-black rounded-lg" style="object-fit: cover; object-position: %s; %s" />',
                    $this->resource->photo, $this->resource->photo_position, $dimensions
                );
            })->asHtml(),

            Select::make('Photo Position')->options([
                'top' => 'Top',
                'center' => 'Center',
                'bottom' => 'Bottom',
            ])->hideFromIndex(),

            Text::make('Public Url', function () {
                $url = $this->resource->public_url;

                return sprintf('<a href="%s" target="_blank" class="no-underline dim text-primary font-bold">View on Website</a>', $url);
            })->asHtml()->showOnUpdating(),

            Boolean::make('Has Photo')->onlyOnIndex(),
            Text::make('Lat')->hideFromIndex(),
            Text::make('Long')->hideFromIndex(),
            HasMany::make('Stories'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
