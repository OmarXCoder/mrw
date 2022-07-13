<template>
    <form>
        <div class="border-t p-6 border-gray-100 dark:border-gray-700">
            <label for="content" class="inline-block leading-tight mb-2">Page Content</label>
            <Trix
                id="content"
                name="trix-page-content-filed"
                :value="form.pageContent"
                :with-files="true"
                @change="(value) => (form.pageContent = value)"
                @file-added="handleFileAdded"
                @file-removed="handleFileRemoved"
                class="tw-min-h-[480px]"
            />
            <div class="mt-2 tw-text-red-500" v-if="form.errors.has('pageContent')">
                {{ form.getError('pageContent') }}
            </div>
        </div>

        <slot name="footer" :submit="submit" />
    </form>
</template>

<script setup>
import { reactive, inject, watch } from 'vue';
import { handleFileAdded, handleFileRemoved } from '@/mixins/trix-attachment-upload';

const emit = defineEmits(['submited']);

const { pageTitle } = inject('newReportPage');
const { baseUrl, report, addReportPage } = inject('tool');
const { id: reportId, reportableId, reportableType } = report;

const form = reactive(
    Nova.form({
        contentType: 'rich-text',
        pageTitle: '',
        pageContent: '',
    })
);

const url = () =>
    `${baseUrl}/reports/${reportId}/pages?reportableType=${reportableType}&reportableId=${reportableId}`;

const submit = () => {
    form.post(url(), { preserveSate: true }).then((response) => {
        addReportPage(response.data);

        emit('submited');
    });
};

watch(pageTitle, (nVal, oVal) => {
    form.pageTitle = nVal;
});
</script>
