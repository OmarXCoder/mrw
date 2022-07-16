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

                <OutlineButton
                    v-if="reportPages.length > 0"
                    class="mr-3 flex-shrink-0"
                    @click="viewReport"
                    v-tooltip="__('View Report')"
                >
                    <Icon type="eye" />
                </OutlineButton>

                <DefaultButton
                    v-if="can.createReportPages"
                    @click="showCreateReportPageModal = true"
                    class="flex-shrink-0"
                >
                    <span class="inline-block">{{ __('Create Report Page') }}</span>
                </DefaultButton>
            </div>
        </div>

        <ReportPages @page-deleted="handleTokenDeleted" />

        <CreateReportPageModal
            v-if="can.createReportPages"
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

const baseUrl = `/nova-vendor/report-page-generator`;
const report = props.panel.fields[0].report;
const can = props.panel.fields[0].can;
const reportPages = ref([]);
const showCreateReportPageModal = ref(false);

provide('tool', {
    baseUrl,
    report,
    can,
    reportPages,
    addReportPage: (page) => {
        reportPages.value.push(page);
        setTimeout(() => {
            window.scrollTo({ top: document.body.offsetHeight });
        }, 250);
    },
    deleteReportPage,
    movePageUp,
    movePageDown,
    showCreateReportPageModal: () => (showCreateReportPageModal.value = true),
});

function deleteReportPage(page) {
    Nova.request()
        .delete(`${baseUrl}/report-pages/${page.id}`)
        .then((res) => {
            reportPages.value = reportPages.value.filter((item) => item !== page);
        });
}

function movePageUp(page) {
    Nova.request()
        .patch(`${baseUrl}/report-pages/${page.id}/up`)
        .then((res) => {
            reportPages.value = res.data.data;
        });
}

function movePageDown(page) {
    Nova.request()
        .patch(`${baseUrl}/report-pages/${page.id}/down`)
        .then((res) => {
            reportPages.value = res.data.data;
        });
}

onMounted(() => {
    Nova.request()
        .get(`${baseUrl}?resourceId=${report.id}`)
        .then((res) => {
            reportPages.value = res.data.data;
        });
});

const viewReport = () => {
    Nova.visit(`/report-page-generator/reports/${report.id}/view?uuid=${report.uuid}`);
};

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
