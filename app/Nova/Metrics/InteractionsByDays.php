<?php
namespace App\Nova\Metrics;

use App\Models\Event;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class InteractionsByDays extends Trend
{
    public function name()
    {
        return __('Interactions For The Past');
    }

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Event::class, 'timestamp')
            ->showSumValue()
            ->format('0,0');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => __('Day'),
            2 => __('2 Days'),
            3 => __('3 Days'),
            7 => __('7 Days'),
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
            120 => __('120 Days'),
            180 => __('180 Days'),
            365 => __('365 Days'),
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
        return 'total-interactions';
    }
}
