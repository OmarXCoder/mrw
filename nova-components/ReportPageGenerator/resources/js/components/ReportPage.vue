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
            <component :is="pageTypes[page.content_type]" :page="page" />
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
import ChartPage from '@/components/page-types/ChartPage.vue';
import RichTextPage from '@/components/page-types/RichTextPage.vue';

const props = defineProps({
    page: { type: Object },
});

const pageTypes = {
    chart: ChartPage,
    'rich-text': RichTextPage,
};

const showDeleteConfirmation = ref(false);

const { deleteReportPage } = inject('tool');
</script>
