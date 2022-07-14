<template>
    <Line
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
import { Line } from 'vue-chartjs';
import chartProps from '@/mixins/chart-props';

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, PointElement, CategoryScale);

const props = defineProps({
    ...chartProps,
});

const chartOptions = merge(props.options, {
    plugins: {
        datalabels: {
            backgroundColor: (context) => context.dataset.backgroundColor,
            color: 'white',
            borderRadius: 4,
            padding: 6,
            align: 'end',
            anchor: 'end',
        },
    },
});
</script>
