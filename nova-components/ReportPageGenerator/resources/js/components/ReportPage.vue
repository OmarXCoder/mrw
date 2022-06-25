<template>
    <div class="px-6 py-4 mb-8">
        <Chart :config="chartConfig" />

        <div class="plain-html" v-html="page.content"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Chart from './Chart.vue';

const emit = defineEmits(['deleted']);

const props = defineProps({
    page: { type: Object },
});

const chartConfig = {
    heading: props.page.heading,
    data: props.page.meta?.chart?.data,
    hooks: props.page.meta?.chart?.hooks,
    chartId: props.page.meta?.chart?.chartId,
};
</script>
