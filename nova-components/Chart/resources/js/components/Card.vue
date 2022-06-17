<template>
    <Card class="px-6 py-4">
        <div class="h-6 flex items-center mb-4">
            <h3 class="mr-3 leading-tight text-sm font-bold">{{ options.title }}</h3>
        </div>

        <div class="relative">
            <div :id="`chart-${options.chartId}`" :style="`height: ${options.chartHeight}`"></div>
        </div>
    </Card>
</template>

<script setup>
import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
import { onMounted } from 'vue';

const props = defineProps({
    card: { type: Object },
});

const defaultOptions = {
    chartId: 'chart',
    chartHeight: '400px',
    title: 'Chart Title',
    prop: 'prop',
    colors: ['#0FA5E9', '#F99037'],
    url: null,
};

const options = Object.assign({}, defaultOptions, props.card);

// Customizing the Chart
const hooks = new ChartisanHooks();

hooks.colors(options.colors).legend(false);

if (options.datasets) {
    hooks['datasets'](options.datasets);
}

onMounted(() => {
    const chart = new Chartisan({
        el: `#chart-${options.chartId}`,
        url: options.url,
        hooks,
    });
});
</script>
