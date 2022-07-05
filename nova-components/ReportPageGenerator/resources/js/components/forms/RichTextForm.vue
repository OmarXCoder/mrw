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

const emit = defineEmits(['submited']);

const { pageTitle } = inject('newReportPage');
const { report, addReportPage } = inject('tool');
const { id: report_id, reportable_id, reportable_type } = report;

const form = reactive(
    Nova.form({
        contentType: 'rich-text',
        pageTitle: '',
        pageContent: '',
    })
);

const reportableType = reportable_type
    .substring(reportable_type.lastIndexOf('\\') + 1, reportable_type.length)
    .toLowerCase();

const url = () =>
    `/nova-vendor/report-page-generator/reports/${report_id}/pages?reportableType=${reportable_type}&reportableId=${reportable_id}`;

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
