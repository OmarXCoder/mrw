<template>
    <div class="relative">
        <button
            @click="showDeleteConfirmation = true"
            class="absolute text-red-400"
            style="top: 20px; right: 20px"
        >
            <Icon type="trash" />
        </button>
        <div class="p-8 bg-white rounded-lg" :id="`report-page-${page.id}`">
            <h1 class="text-2xl">{{ page.heading }}</h1>
            <Chart :config="chartConfig" />

            <div class="plain-html mt-8" v-html="page.content"></div>
        </div>

        <DeleteResourceModal
            :show="showDeleteConfirmation"
            @confirm="deleteReportPage(page)"
            @close="showDeleteConfirmation = false"
        />
    </div>
</template>

<script setup>
import { ref, inject } from 'vue';
import Chart from './Chart.vue';

const props = defineProps({
    page: { type: Object },
});

const showDeleteConfirmation = ref(false);

const { deleteReportPage } = inject('tool');

const chartConfig = {
    heading: props.page.heading,
    data: props.page.meta?.chart?.data,
    hooks: props.page.meta?.chart?.hooks,
    chartId: props.page.meta?.chart?.chartId,
};
</script>
