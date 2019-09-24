<?php

namespace KSUGMap\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Story extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'KSUGMap\Story';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'subject';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'subject', 'role', 'day',
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
            Text::make('Subject'),
            Text::make('Role'),
            Text::make('Day'),
            Text::make('Public Url', function () {
                $url = $this->resource->public_url;

                return sprintf('<a href="%s" target="_blank" class="no-underline dim text-primary font-bold">View on Website</a>', $url);
            })->asHtml(),

            Text::make('Full Story Link')->hideFromIndex(),
            Text::make('Audio')->hideFromIndex(),
            Text::make('Audio Preview', function () {
                if (empty($this->resource->audio)) {
                    return 'N/A';
                }

                return sprintf('<audio src="%s" controls />', $this->resource->audio);
            })->asHtml()->hideFromIndex(),
            Textarea::make('Content'),
            BelongsTo::make('Place')->hideFromIndex(),
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
