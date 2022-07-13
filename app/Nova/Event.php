<?php
namespace App\Nova;

use App\Nova\Filters\ActionTypeFilter;
use App\Nova\Filters\EventTypeFilter;
use App\Nova\Metrics\TotalEvents;
use App\Nova\Metrics\InteractionsByDays;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mrw\Chart\Chart;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\ExportAsCsv;

class Event extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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

            BelongsTo::make('Action Type'),

            BelongsTo::make('Event Type'),

            BelongsTo::make('Attendee'),

            BelongsTo::make('App'),

            BelongsTo::make('Show'),

            KeyValue::make('Meta')->rules('json'),
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
        return [
            TotalEvents::make()->width('1/3'),

            InteractionsByDays::make()->width('2/3'),

            Chart::make()
                ->width('full')
                ->height('dynamic')
                ->url('/api/chart/events_per_event_type_chart')
                ->title('Interactions Per Event Type')
                ->options([
                    'chartId' => Str::uuid(),
                    'chartHeight' => '500px',
                ]),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new ActionTypeFilter,
            new EventTypeFilter,
        ];
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
        return [
            ExportAsCsv::make()
                ->withFormat(fn ($model) => [
                    'ID' => $model->getKey(),
                    'Action Type' => $model->actionType->name,
                    'Event Type' => $model->eventType->name,
                    'App' => $model->app->name,
                    'Show' => $model->show->name,
                    'Timestamp' => $model->timestamp->toDateTimeLocalString(),
                ])
                ->nameable('events-exported-' . today()->toDateString())
                ->onlyOnIndex(),
        ];
    }
}
