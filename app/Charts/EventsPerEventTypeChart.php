<?php

declare(strict_types=1);
namespace App\Charts;

use App\Models\Event;
use App\Models\EventType;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsPerEventTypeChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $events = Event::select('event_code', DB::raw('COUNT(event_code) as events_per_type'))
            ->groupBy('event_code');

        $items = EventType::joinSub($events, 'events', function ($join) {
            $join->on('events.event_code', '=', 'event_types.code');
        })->get();

        return Chartisan::build()
            ->labels($items->pluck('name')->toArray())
            ->dataset('Events', $items->pluck('events_per_type')->toArray());
    }
}
