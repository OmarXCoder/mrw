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
};

const defaultHooks = {
    colors: null,
};

const options = Object.assign({}, defaultOptions, props.card);

const hooks = Object.assign({}, defaultHooks, props.card.hooks);

// Customizing the Chart
const chartisanHooks = new ChartisanHooks();

for (let hook in hooks) {
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
