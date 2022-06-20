<?php
namespace App\Nova\Metrics;

use App\Models\Attendee;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class AttendeesPerApp extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $query = Attendee::where('attendees.show_id', $request->resourceId)
            ->join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->join('apps', 'apps.id', '=', 'app_attendee.app_id')
            ->select('attendees.id', 'apps.name');

        return $this->count($request, $query, 'name');
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'attendees-per-app';
    }
}
