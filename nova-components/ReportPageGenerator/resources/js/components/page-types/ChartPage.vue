<template>
    <div>
        <h1 v-if="page.title" class="text-2xl tw-mb-6 tw-leading-none">{{ page.title }}</h1>

        <Chart :chart="page.meta?.chart" />

        <div v-if="page.content" class="plain-html tw-mt-8" v-html="page.content"></div>
    </div>
</template>

<script setup>
import Chart from '@/components/Chart.vue';

defineProps({
    page: { type: Object, default: {} },
});
</script>
