<?php

namespace App\Nova\Metrics;

use App\Models\Event;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class AppAttendeeInteractions extends Trend
{
    public $name = 'Interactions';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByHours(
            $request,
            Event::where('app_id', $request->resourceId),
            'timestamp'
        );
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => __('Last Hour'),
            2 => __('Two Hours'),
            6 => __('Six Hours'),
            12 => __('12 Hours'),
            24 => __('24 Hours'),
            48 => __('48 Hours'),
            72 => __('72 Hours'),
            96 => __('96 Hours'),
        ];
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
        return 'app-attendee-interactions';
    }
}
