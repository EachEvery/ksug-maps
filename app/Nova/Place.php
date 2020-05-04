<?php

namespace KSUGMap\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Place extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \KSUGMap\Place::class;

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
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            Select::make('Photo Position')->options([
                'top' => 'Top',
                'center' => 'Center',
                'bottom' => 'Bottom',
            ])->hideFromIndex(),

            Text::make('Public Url', function () {
                $url = $this->resource->public_url;

                return sprintf('<a href="%s" target="_blank" class="no-underline dim text-primary font-bold">View on Website</a>', $url);
            })->asHtml()->showOnUpdating(),

            Code::make('Embed Code')->onlyOnDetail(),

            Images::make('Photos', 'place_photos')->croppable(false)
                ->customPropertiesFields([
                    Text::make('Alt Text'),
                    Text::make('Photo Caption'),
                ]),

            Text::make('Lat')->hideFromIndex(),
            Text::make('Long')->hideFromIndex(),

            HasMany::make('Stories'),
            MorphMany::make('Comments')->exceptOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
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
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
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
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
