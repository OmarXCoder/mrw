<template>
    <Pie
        :chart-options="options"
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
import { Pie } from 'vue-chartjs';
import chartProps from '@/mixins/chart-props';

import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
    ...chartProps,
});
</script>
