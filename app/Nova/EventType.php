<?php
namespace App\Nova;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mrw\ReportPageGenerator\ChartCard;

class EventType extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\EventType::class;

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
        'code', 'name',
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
            Number::make('Code')
                ->sortable()
                ->rules('required', 'numeric')
                ->creationRules('unique:event_types,code')
                ->updateRules([Rule::unique('event_types', 'code')->ignore($this->code, 'code')])
                ->textAlign('left'),

            Text::make('Name')
                ->rules('required'),

            Textarea::make('Description')->hideFromIndex(),
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
            ChartCard::make()
                ->width(Card::FULL_WIDTH)
                ->chartConfig($this->createEventsPerEventTypeChart()),
        ];
    }

    protected function createEventsPerEventTypeChart(): array
    {
        $result = Event::join('event_types', 'events.event_code', '=', 'event_types.code')
            ->select('name', DB::raw('COUNT(event_code) as events_count'))
            ->groupBy('event_code')
            ->pluck('events_count', 'name');

        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'bar',
            'title' => 'Interactions Per Event Type',
            'width' => 712,
            'height' => 500,
            'datasetIdKey' => 'label',
            'data' => [
                'labels' => $result->keys()->toArray(),
                'datasets' => [
                    [
                        'label' => 'Events',
                        'data' => $result->values()->toArray(),
                        'backgroundColor' => ['#5E43CC'],
                    ],
                ],
            ],
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
        return [
            ExportAsCsv::make()
                ->withFormat(fn ($model) => [
                    'Code' => $model->getKey(),
                    'Name' => $model->name,
                    'Description' => $model->description,
                ])
                ->nameable('event-types-exported-' . today()->toDateString())
                ->onlyOnIndex(),
        ];
    }
}
