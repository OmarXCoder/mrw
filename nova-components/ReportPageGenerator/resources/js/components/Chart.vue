<template>
    <div class="relative">
        <div :id="`chart-${options.chartId}`" :style="`height: ${options.chartHeight}`"></div>
    </div>
</template>

<script setup>
import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
import { onMounted } from 'vue';

const props = defineProps({
    config: { type: Object },
});

const defaultOptions = {
    chartId: 'chartisan-chart',
    chartHeight: '400px',
    heading: 'Chart Heading',
};

const defaultHooks = {
    colors: null,
    legend: true,
    responsive: true,
};

const options = Object.assign({}, defaultOptions, props.config);

const hooks = Object.assign({}, defaultHooks, props.config.hooks);

console.log(hooks);

// Customizing the Chart
const chartisanHooks = new ChartisanHooks();

for (let hook in hooks) {
    if (typeof chartisanHooks[hook] !== 'function') {
        continue;
    }

    if (hooks[hook] !== null) {
        chartisanHooks[hook](hooks[hook]);
    } else {
        chartisanHooks[hook]();
    }
}

onMounted(() => {
    const chartOptions = {
        el: `#chart-${options.chartId}`,
        hooks: chartisanHooks,
    };

    if (options.url) {
        chartOptions['url'] = options.url;
    } else if (options.data) {
        chartOptions['data'] = options.data;
    }

    new Chartisan(chartOptions);
});
</script>
