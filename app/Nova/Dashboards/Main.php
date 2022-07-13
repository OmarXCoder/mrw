<?php
namespace App\Nova\Dashboards;

use App\Nova\Metrics\TotalApps;
use App\Nova\Metrics\TotalAttendees;
use App\Nova\Metrics\TotalClients;
use App\Nova\Metrics\TotalEvents;
use App\Nova\Metrics\InteractionsByDays;
use App\Nova\Metrics\TotalShows;
use App\Nova\Metrics\UsersPerType;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return Auth::user()->isClientTeamMember() ? $this->clientCards() : [
            TotalClients::make()->width('1/3')->defaultRange('ALL'),
            TotalShows::make()->width('1/3')->defaultRange('ALL'),
            TotalApps::make()->width('1/3')->defaultRange('ALL'),
            TotalAttendees::make()->width('1/4')->defaultRange('ALL'),
            TotalEvents::make()->width('1/4')->defaultRange('ALL'),
            UsersPerType::make()->width('1/2'),
            InteractionsByDays::make()->width('full'),
        ];
    }

    public function clientCards(): array
    {
        return [
            TotalShows::make()->width('1/4')->defaultRange('ALL'),
            TotalApps::make()->width('1/4')->defaultRange('ALL'),
            TotalAttendees::make()->width('1/4')->defaultRange('ALL'),
            TotalEvents::make()->width('1/4')->defaultRange('ALL'),
            InteractionsByDays::make()->width('full'),
        ];
    }
}
