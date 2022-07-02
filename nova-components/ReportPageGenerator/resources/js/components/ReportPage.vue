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
            <component
                :is="chartComponents[chart.type]"
                :chart-id="chart.id"
                :height="chart.height"
                :width="chart.width"
                :data="chart.data"
                :options="defaultChartOptions"
            />

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
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import PieChart from '@/components/charts/PieChart.vue';

const props = defineProps({
    page: { type: Object },
});

const chartComponents = {
    bar: BarChart,
    line: LineChart,
    pie: PieChart,
};

const showDeleteConfirmation = ref(false);

const { deleteReportPage } = inject('tool');

const chart = props.page.meta?.chart;

const defaultChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
        },
    },
    plugins: {
        title: {
            text: 'Custom Chart Title',
            display: true,
            padding: {
                bottom: 24,
            },
            font: {
                size: 16,
            },
        },
    },
};
</script>
