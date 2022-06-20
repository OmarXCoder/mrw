<?php
namespace App\Nova;

use App\Models\Attendee;
use App\Nova\Metrics\AppAttendeeInteractions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Card;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mrw\ApiTokenGenerator\ApiTokenGenerator;
use Mrw\Chart\Chart;

class App extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\App::class;

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
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount(['attendees']);
    }

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

            Text::make('Name')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Client')
                ->canSee(fn ($request) => !$request->user()->isClientTeamMember())
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->showOnPreview(),

            BelongsTo::make('Show')
                ->sortable()
                ->searchable(),

            Number::make('# Attendees', 'attendees_count')
                ->sortable()
                ->onlyOnIndex(),

            Text::make('Last Action - Event', function () {
                $lastEvent = $this->lastEvent;
                if (!$lastEvent) {
                    return '—';
                }

                $text = sprintf(
                    '%s - %s',
                    $lastEvent?->actionType->name,
                    $lastEvent?->eventType->name
                );

                return "<div class='inline-flex items-center whitespace-nowrap h-6 px-2 rounded-full uppercase text-xs font-bold bg-green-100 text-green-600 dark:bg-green-500 dark:text-green-900'>{$text}</div>";
            })->asHtml(),

            HasMany::make('Attendees'),

            HasMany::make('Events'),

            ApiTokenGenerator::make()->canSee(fn ($request) => $request->user()->hasPermissionTo('api_tokens.create')),
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
            AppAttendeeInteractions::make()
                ->width('full')
                ->onlyOnDetail(),

            $this->createParticipantsByCountryChart($request)
                ->width('full')
                ->height('dynamic')
                ->onlyOnDetail(),

            $this->createParticipantsByCompanyChart($request)
                ->width('full')
                ->height('dynamic')
                ->onlyOnDetail(),

            $this->createParticipantsByProfessionChart($request)
                ->width('full')
                ->height('dynamic')
                ->onlyOnDetail(),
        ];
    }

    private function createParticipantsByCountryChart(NovaRequest $request): Card
    {
        $participantsByCountry = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.country', DB::raw('COUNT(attendees.country) as attendees_count'))
            ->groupBy('attendees.country')
            ->where('app_attendee.app_id', $request->resourceId)
            ->get();

        return Chart::make()
            ->title('Participants By Country')
            ->data([
                'chart' => ['labels' => $participantsByCountry->pluck('country')->toArray()],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByCountry->pluck('attendees_count')->toArray(),
                    ],
                ],
            ])
            ->hooks([
                'beginAtZero' => true,
            ]);
    }

    private function createParticipantsByCompanyChart(NovaRequest $request): Card
    {
        $participantsByCompany = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.company', DB::raw('COUNT(attendees.company) as attendees_count'))
            ->groupBy('attendees.company')
            ->where('app_attendee.app_id', $request->resourceId)
            ->get();

        return Chart::make()
            ->title('Participants By Company')
            ->data([
                'chart' => ['labels' => $participantsByCompany->pluck('company')->toArray()],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByCompany->pluck('attendees_count')->toArray(),
                    ],
                ],
            ])
            ->hooks([
                'beginAtZero' => true,
            ]);
    }

    private function createParticipantsByProfessionChart(NovaRequest $request): Card
    {
        $participantsByProfession = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.profession', DB::raw('COUNT(attendees.profession) as attendees_count'))
            ->groupBy('attendees.profession')
            ->where('app_attendee.app_id', $request->resourceId)
            ->get();

        return Chart::make()
            ->title('Participants By Profession')
            ->data([
                'chart' => ['labels' => $participantsByProfession->pluck('profession')->toArray()],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByProfession->pluck('attendees_count')->toArray(),
                    ],
                ],
            ])
            ->hooks([
                'beginAtZero' => true,
            ]);
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
