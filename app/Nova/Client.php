<?php
namespace App\Nova;

use App\Nova\Metrics\ClientAppsCount;
use App\Nova\Metrics\ClientAttendeesCount;
use App\Nova\Metrics\ClientShowsCount;
use App\Nova\Metrics\UsersPerRole;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ExportAsCsv;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Client extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Client::class;

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

    // public static $showColumnBorders = true;

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount(['shows', 'apps', 'users']);
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

            Text::make('Name')->sortable(),

            Number::make('Team Members', 'users_count')->onlyOnIndex()->sortable(),

            Number::make('Shows', 'shows_count')->onlyOnIndex()->sortable(),

            Number::make('Applications', 'apps_count')->onlyOnIndex()->sortable(),

            HasMany::make('Shows'),

            HasMany::make('Apps'),

            HasMany::make('Team Members', 'users', User::class),
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
            ClientShowsCount::make()->width('1/4')->onlyOnDetail(),
            ClientAppsCount::make()->width('1/4')->onlyOnDetail(),
            ClientAttendeesCount::make()->width('1/4')->onlyOnDetail(),
            UsersPerRole::make()->width('1/4')->onlyOnDetail(),
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
                    'ID' => $model->getKey(),
                    'Name' => $model->name,
                    'Created At' => $model->created_at->toDateString(),
                ])
                ->nameable('clients-exported-' . today()->toDateString())
                ->onlyOnIndex(),
        ];
    }
}
