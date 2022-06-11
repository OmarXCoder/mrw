<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\TotalApps;
use App\Nova\Metrics\TotalClients;
use App\Nova\Metrics\TotalInteractions;
use App\Nova\Metrics\TotalShows;
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
            TotalClients::make()->defaultRange('ALL'),
            TotalShows::make()->defaultRange('ALL'),
            TotalApps::make()->defaultRange('ALL'),
            TotalInteractions::make()->width('full'),
        ];
    }
}
