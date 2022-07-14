<template>
    <Bar
        :chart-options="chartOptions"
        :chart-data="data"
        :chart-id="chartId"
        :dataset-id-key="datasetIdKey"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
    />
</template>

<script setup>
import { merge } from 'lodash';
import { Bar } from 'vue-chartjs';
import chartProps from '@/mixins/chart-props';

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    ...chartProps,
});

const chartOptions = merge(props.options, {
    plugins: {
        datalabels: {
            color: '#64748b',
            font: {
                weight: 'thin',
            },
            align: 'end',
            anchor: 'end',
        },
    },
});
</script>
