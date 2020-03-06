<?php

namespace KSUGMap\Nova;

use Illuminate\Http\Request;
use KSUGMap\Nova\Actions\ApproveComments;
use KSUGMap\Nova\Actions\UnapproveComments;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Comment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \KSUGMap\Comment::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'author';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'author', 'email', 'text',
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
            Text::make('Author')->readonly(),
            Text::make('Email')->readonly(),
            Textarea::make('Text')->readonly(),
            Boolean::make('Is Approved')->hideWhenCreating()->hideWhenUpdating(),
            Date::make('Created At')->readonly(),
            BelongsTo::make('Place'),
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
        return [new ApproveComments(), new UnapproveComments()];
    }
}
