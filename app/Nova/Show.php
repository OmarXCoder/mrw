<?php
namespace App\Nova;

use App\Models\Show as ShowModel;
use App\Nova\Filters\ShowStatusFilter;
use App\Nova\Filters\TimestampFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Show extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Show::class;

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
        return $query->withCount(['apps', 'attendees']);
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

            Text::make('Show Name', 'name')
                ->sortable()
                ->rules('required')
                ->showOnPreview(),

            Text::make('Organizer')
                ->rules('required')
                ->showOnPreview(),

            BelongsTo::make('Client')
                ->canSee(fn ($request) => !$request->user()->isClientTeamMember())
                ->rules('required', 'exists:clients,id')
                ->showOnPreview(),

            DateTime::make('Start Date')
                ->creationRules('required', 'after_or_equal:today')
                ->updateRules('required')
                ->showOnPreview()
                ->hideFromIndex(),

            DateTime::make('End Date')
                ->rules('required', 'after_or_equal:start_date')
                ->showOnPreview()
                ->hideFromIndex(),

            Number::make('Applications', 'apps_count')->onlyOnIndex()->sortable(),

            Number::make('Attendees', 'attendees_count')->onlyOnIndex()->sortable(),

            Badge::make('Status')->map([
                ShowModel::STATUS_UPCOMMING => 'warning',
                ShowModel::STATUS_ACTIVE => 'success',
                ShowModel::STATUS_ENDED => 'danger',
            ])->showOnPreview()->sortable(),

            HasMany::make('Apps'),

            HasMany::make('Attendees'),
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
        return [
            new ShowStatusFilter,
            new TimestampFilter('start_date'),
            new TimestampFilter('end_date'),
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
        return [];
    }
}
