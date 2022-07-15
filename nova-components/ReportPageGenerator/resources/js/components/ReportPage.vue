<template>
    <div class="relative">
        <div
            v-if="showActions && can.deleteReportPages"
            class="tw-absolute tw-top-5 tw-right-5 tw-flex"
        >
            <button @click="showDeleteConfirmation = true" class="text-red-400">
                <Icon type="trash" />
            </button>
        </div>

        <div
            v-if="showActions && can.editReportPages"
            class="tw-w-12 tw-flex tw-flex-col tw-justify-center tw-absolute tw-top-5 -tw-right-12"
        >
            <button
                v-if="showUpArrow"
                @click="movePageUp(page)"
                class="tw-text-gray-400 hover:tw-text-blue-400 tw-transition-colors tw-py-1"
            >
                <Icon type="arrow-up" />
            </button>

            <button
                v-if="showDownArrow"
                @click="movePageDown(page)"
                class="tw-text-gray-400 hover:tw-text-blue-400 tw-transition-colors tw-py-1"
            >
                <Icon type="arrow-down" />
            </button>
        </div>

        <div class="p-8 bg-white rounded-lg" :id="`report-page-${page.id}`">
            <component :is="pageTypes[page.content_type]" :page="page" />
        </div>

        <DeleteResourceModal
            v-if="showActions && can.deleteReportPages"
            :show="showDeleteConfirmation"
            @confirm="deleteReportPage(page)"
            @close="showDeleteConfirmation = false"
        />
    </div>
</template>

<script setup>
import { ref, inject } from 'vue';
import ChartPage from '@/components/page-types/ChartPage.vue';
import RichTextPage from '@/components/page-types/RichTextPage.vue';

const props = defineProps({
    page: { type: Object },
    showActions: { type: Boolean, default: true },
    showUpArrow: { type: Boolean, default: false },
    showDownArrow: { type: Boolean, default: false },
});

const pageTypes = {
    chart: ChartPage,
    'rich-text': RichTextPage,
};

const showDeleteConfirmation = ref(false);

const { deleteReportPage, movePageUp, movePageDown, can } = inject('tool');
</script>
