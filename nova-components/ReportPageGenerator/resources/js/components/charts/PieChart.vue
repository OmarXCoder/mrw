<template>
    <Pie
        :chart-options="chartOptions"
        :chart-data="data"
        :chart-id="chartId"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
    />
</template>

<script setup>
import { merge } from 'lodash';
import { Pie } from 'vue-chartjs';
import chartProps from '@/mixins/chart-props';

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale);

const props = defineProps({
    ...chartProps,
});

const chartOptions = merge(props.options, {
    plugins: {
        datalabels: {
            color: '#ffffff',
            font: {
                size: 16,
            },
        },
    },
});
</script>
