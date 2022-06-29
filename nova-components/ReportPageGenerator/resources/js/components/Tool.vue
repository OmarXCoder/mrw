<template>
    <div>
        <div class="flex mb-3">
            <h2 class="text-90 font-normal text-xl md:text-2xl flex items-center">
                {{ __('Report Pages') }}
            </h2>
            <!-- Toolbar -->
            <div class="flex md:justify-end ml-auto">
                <OutlineButton
                    v-if="reportPages.length > 0"
                    class="mr-3 flex-shrink-0"
                    @click="downloadPdf"
                    v-tooltip="__('Download PDF')"
                >
                    <Icon type="download" />
                </OutlineButton>

                <DefaultButton @click="showCreateReportPageModal = true" class="flex-shrink-0">
                    <span class="inline-block">{{ __('Create Report Page') }}</span>
                </DefaultButton>
            </div>
        </div>

        <ReportPages @page-deleted="handleTokenDeleted" />

        <CreateReportPageModal
            :show="showCreateReportPageModal"
            @cancel="showCreateReportPageModal = false"
            @created="showCreateReportPageModal = false"
        />
    </div>
</template>

<script setup>
import ReportPages from './ReportPages.vue';
import CreateReportPageModal from './CreateReportPageModal.vue';
import { ref, onMounted, provide } from 'vue';
import jsPDF from 'jspdf';
import html2canvas from 'html2canvas';

const props = defineProps({
    panel: Object,
});

const report = props.panel.fields[0].report;
const reportPages = ref([]);
const showCreateReportPageModal = ref(false);

provide('tool', {
    report,
    reportPages,
    addReportPage: (page) => reportPages.value.push(page),
    showCreateReportPageModal: () => (showCreateReportPageModal.value = true),
});

const url = () => `/nova-vendor/report-page-generator?resourceId=${report.id}`;

onMounted(() => {
    Nova.request()
        .get(url())
        .then((res) => {
            reportPages.value = res.data.data;
        });
});

const downloadPdf = async () => {
    const doc = new jsPDF({
        unit: 'pt',
        compressPdf: true,
    });

    for (let page of reportPages.value) {
        let pageEl = document.getElementById(`report-page-${page.id}`);

        await html2canvas(pageEl, {
            width: pageEl.offsetWidth,
        }).then((canvas) => {
            doc.internal.pageSize.width = pageEl.offsetWidth;
            doc.internal.pageSize.height = pageEl.offsetHeight;
            doc.addImage(
                canvas.toDataURL('image/jpeg'),
                'JPEG',
                0,
                0,
                pageEl.offsetWidth,
                pageEl.offsetHeight
            );
        });

        if (reportPages.value.indexOf(page) < reportPages.value.length - 1) {
            doc.addPage();
        }
    }

    doc.save(`${report.name}-${new Date().toLocaleDateString()}`.replace(/[\/\s]/g, '-'));
};
</script>
