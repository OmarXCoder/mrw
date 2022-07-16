<template>
    <component
        :is="chartTypes[chart.type]"
        :chart-id="chart.id"
        :height="chart.height"
        :width="chart.width"
        :data="chart.data"
        :options="defaultChartOptions"
        :plugins="chartPlugins"
    />
</template>

<script setup>
import chartDataLabels from 'chartjs-plugin-datalabels';
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import PieChart from '@/components/charts/PieChart.vue';

const props = defineProps({
    chart: { type: Object, default: {} },
});

const chartTypes = {
    bar: BarChart,
    line: LineChart,
    pie: PieChart,
};

const defaultChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
        },
    },
    plugins: {
        title: {
            text: props.chart.title,
            display: true,
            font: {
                size: 18,
                weight: 'normal',
            },
        },
        datalabels: {
            font: {
                size: 14,
                weight: 'normal',
            },
            offset: 4,
            formatter: Math.round,
        },
    },
};

/**
 * A plugin to add a space between the legend and the chart itself
 */
const legendMarginPlugin = {
    id: 'legendMarginPlugin',
    beforeInit(chart, legend, options) {
        const fitValue = chart.legend.fit;
        chart.legend.fit = function () {
            fitValue.bind(chart.legend)();
            return (this.height += 20);
        };
    },
};

const chartPlugins = [chartDataLabels, legendMarginPlugin];
</script>
