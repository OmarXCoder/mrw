<?php
namespace App\Nova\Dashboards;

use App\Nova\Metrics\TotalApps;
use App\Nova\Metrics\TotalAttendees;
use App\Nova\Metrics\TotalClients;
use App\Nova\Metrics\TotalInteractions;
use App\Nova\Metrics\TotalShows;
use App\Nova\Metrics\UsersPerType;
use Laravel\Nova\Cards\Help;
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
        return [
            // new Help,
            TotalClients::make()->width('1/3')->defaultRange('ALL'),
            TotalShows::make()->width('1/3')->defaultRange('ALL'),
            TotalApps::make()->width('1/3')->defaultRange('ALL'),
            TotalAttendees::make()->width('1/3')->defaultRange('ALL'),
            UsersPerType::make()->width('2/3'),
            TotalInteractions::make()->width('full'),
        ];
    }
}
