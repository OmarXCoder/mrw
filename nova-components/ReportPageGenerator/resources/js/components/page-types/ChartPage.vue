<template>
    <div>
        <h1 v-if="page.title" class="text-2xl tw-mb-6 tw-leading-none">{{ page.title }}</h1>

        <component
            :is="chartTypes[chart.type]"
            :chart-id="chart.id"
            :height="chart.height"
            :width="chart.width"
            :data="chart.data"
            :options="defaultChartOptions"
        />

        <div v-if="page.content" class="plain-html tw-mt-8" v-html="page.content"></div>
    </div>
</template>

<script setup>
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import PieChart from '@/components/charts/PieChart.vue';

const props = defineProps({
    page: { type: Object, default: {} },
});

const chartTypes = {
    bar: BarChart,
    line: LineChart,
    pie: PieChart,
};

const chart = props.page.meta?.chart;

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
            text: chart.title,
            display: true,
            padding: {
                bottom: 24,
            },
            font: {
                size: 16,
                weight: 600,
            },
        },
    },
};
</script>
