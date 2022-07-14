<?php
namespace Mrw\ReportPageGenerator;

use Laravel\Nova\Card;

class ChartCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'ChartCard';
    }

    /**
     * Set Chart's data
     *
     * @param string $chartConfig
     * @return ChartCard
     */
    public function chartConfig(array $chartConfig): ChartCard
    {
        $this->meta['chart'] = $chartConfig;

        return $this;
    }
}
