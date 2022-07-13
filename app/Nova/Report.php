<?php
namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mrw\ReportPageGenerator\ReportPageGenerator;

class Report extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Report::class;

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
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')->rules(['required']),

            Textarea::make('Description'),

            BelongsTo::make('Client')->hideWhenCreating()->hideWhenUpdating(),

            MorphTo::make('Reportable')->types([Show::class, App::class]),

            ReportPageGenerator::make()->withMeta((function (): array {
                $uniqueKey = "{$this->resource->id}-{$this->resource->reportable_type}-{$this->resource->reportable_id}";

                return [
                    'report' => [
                        'id' => $this->resource->id,
                        'uuid' => Hash::make($uniqueKey),
                        'name' => $this->resource->name,
                        'reportableId' => $this->resource->reportable_id,
                        'reportableType' => strtolower(class_basename($this->resource->reportable_type)),
                    ],
                ];
            })()),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
