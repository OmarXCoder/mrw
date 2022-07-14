<template>
    <Card class="px-6 py-4">
        <div v-if="card.title" class="h-6 flex items-center mb-4">
            <h3 class="mr-3 leading-tight text-sm font-bold">{{ card.title }}</h3>
        </div>

        <Chart :chart="card.chart" />
    </Card>
</template>

<script setup>
import Chart from '@/components/Chart.vue';

defineProps({
    card: { type: Object, default: {} },
});
</script>
