<?php
namespace Mrw\Chart;

use Laravel\Nova\Card;

class Chart extends Card
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
        return 'chart';
    }

    /**
     * Options passed to the vue chart component
     *
     * @return Chart
     */
    public function options(array $options)
    {
        return $this->withMeta($options);
    }

    /**
     * Set Chart's card title
     *
     * @param string $title
     * @return Chart
     */
    public function title(string $title): Chart
    {
        $this->meta['title'] = $title;

        return $this;
    }

    /**
     * Set Chart's API URL
     *
     * @param string $url
     * @return Chart
     */
    public function url(string $url): Chart
    {
        $this->meta['url'] = $url;

        return $this;
    }

    /**
     * Set Chart's datasets
     *
     * @param string $datasets
     * @return Chart
     */
    public function datasets(array $datasets): Chart
    {
        $this->meta['datasets'] = $datasets;

        return $this;
    }
}
