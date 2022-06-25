<template>
    <div>
        <div class="flex flex-row mb-6">
            <h2 class="text-2xl w-full">Report Pages</h2>
            <button
                class="shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0"
                dusk="create-button"
                @click="showModal = true"
            >
                <span class="inline-block">{{ __('Create New Report Page') }}</span>
            </button>
        </div>

        <ReportPages @asked-to-create-page="showModal = true" @page-deleted="handleTokenDeleted" />

        <NewReportPageModal
            :show="showModal"
            @cancel="showModal = false"
            @created="showModal = false"
        />
    </div>
</template>

<script setup>
import ReportPages from './ReportPages.vue';
import NewReportPageModal from './NewReportPageModal.vue';
import { ref, onMounted, provide } from 'vue';

const props = defineProps({
    resourceId: Number,
    panel: Object,
});

const reportPages = ref([]);
const showModal = ref(false);

provide('reportId', props.resourceId);
provide('reportPages', {
    reportPages,
    addReportPage: (page) => reportPages.value.push(page),
});
provide('toolMeta', props.panel.fields[0]);

const url = () => `/nova-vendor/report-page-generator?resourceId=${props.resourceId}`;

onMounted(() => {
    Nova.request()
        .get(url())
        .then((res) => {
            reportPages.value = res.data.data;
        });
});
</script>
