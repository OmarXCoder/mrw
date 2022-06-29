<template>
    <div
        class="p-8 mx-auto bg-white rounded-lg mb-6"
        style="max-width: 1024px"
        :id="`report-page-${page.id}`"
    >
        <h1 class="text-2xl">{{ page.heading }}</h1>
        <Chart :config="chartConfig" />

        <div class="plain-html mt-8" v-html="page.content"></div>
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
