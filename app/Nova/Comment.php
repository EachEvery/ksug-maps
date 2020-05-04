<?php

namespace KSUGMap\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use KSUGMap\Nova\Actions\ApproveComments;
use KSUGMap\Nova\Actions\UnapproveComments;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\MorphTo;
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
            Text::make('Preview Media')->displayUsing(function () {
                if ($this->resource->getMedia('comment_media')->count() === 0) {
                    return 'N/A';
                }

                if ($this->resource->media_is_image) {
                    return sprintf('<img src="%s" style="max-width: 15rem; border-radius: 5px " />', $this->resource->media_url);
                }

                return sprintf('<video src="%s" controls style="max-width: 15rem; border-radius: 5px;" />', $this->resource->media_url);
            })->asHtml()->onlyOnDetail(),
            Date::make('Created At')->readonly()->hideFromIndex(),
            Boolean::make('Has Media')->onlyOnIndex(),
            MorphTo::make('Commentable'),
            Files::make('Media', 'comment_media'),
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
