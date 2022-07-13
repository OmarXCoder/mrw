<template>
    <div class="bg-gray-50 dark:bg-gray-800 p-6 tw-min-h-screen">
        <div v-if="reportPages.data.length > 0" class="mx-auto" style="max-width: 1024px">
            <ReportPage
                v-for="(page, index) in reportPages.data"
                :page="page"
                :key="page.id"
                :class="{ 'mb-6': index !== reportPages.data.length - 1 }"
                :show-actions="false"
            />
        </div>
    </div>
</template>

<script setup>
import ReportPage from '@/components/ReportPage.vue';
import { provide } from 'vue';

const props = defineProps({
    reportPages: Object,
});

provide('tool', {
    deleteReportPage: () => {},
    movePageUp: () => {},
    movePageDown: () => {},
});
</script>

<script>
import GuestLayout from '@/layouts/Guest.vue';

export default {
    layout: GuestLayout,
};
</script>
