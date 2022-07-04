<template>
    <form>
        <div class="tw-grid tw-grid-cols-12 tw-gap-4 p-4">
            <div class="tw-col-span-12">
                <h4 class="tw-text-lg tw-font-semibold">Chart Configuration</h4>
            </div>
            <TwInputField
                class="tw-col-span-6"
                label="Title"
                v-model="form.title"
                :error="form.getError('title')"
                required
            />

            <TwSelectField
                class="tw-col-span-6"
                label="Chart Type"
                v-model="form.type"
                :error="form.getError('type')"
                :options="chartTypes"
                required
            />

            <TwSelectField
                class="tw-col-span-3"
                label="Table"
                v-model="form.table"
                :error="form.getError('table')"
                :options="tables[reportableType]"
                required
            />

            <TwSelectField
                v-if="tableColumnsMap[form.table]"
                class="tw-col-span-3"
                label="Column"
                v-model="form.column"
                :error="form.getError('Column')"
                :options="tableColumnsMap[form.table]"
                required
            />

            <TwInputField
                v-else
                class="tw-col-span-3"
                label="Column"
                v-model="form.column"
                :error="form.getError('column')"
                required
            />

            <TwInputField
                class="tw-col-span-3"
                label="Height"
                type="number"
                v-model="form.height"
                :error="form.getError('height')"
            />

            <TwInputField
                class="tw-col-span-3"
                label="Width"
                type="number"
                v-model="form.width"
                :error="form.getError('width')"
            />
            <div class="tw-col-span-12">
                <h4 class="tw-font-bold tw-mb-2">Colors</h4>
                <div class="tw-grid tw-grid-cols-12 tw-gap-4">
                    <div v-for="(color, index) in form.colors" :key="index" class="tw-col-span-2">
                        <input
                            type="color"
                            v-model="form.colors[index]"
                            class="w-full px-2 tw-h-9"
                        />
                    </div>
                    <div v-if="form.type === 'pie'" class="tw-col-span-2">
                        <OutlineButton type="button" @click="addColorInput" class="w-full">
                            <Icon type="plus" />
                        </OutlineButton>
                    </div>
                </div>
            </div>
        </div>

        <div class="tw-border-t p-4 tw-border-gray-100">
            <label for="content" class="inline-block leading-tight mb-2">Notes</label>
            <Trix
                id="content"
                name="trix-page-content-filed"
                :value="form.pageContent"
                :with-files="false"
                @change="(value) => (form.pageContent = value)"
                class="min-h-40"
            />
        </div>

        <slot name="footer" :submit="submit" />
    </form>
</template>

<script setup>
import { reactive, inject, ref } from 'vue';

const emit = defineEmits(['submited']);

const props = defineProps({
    form: Object,
});

const tables = {
    app: ['app-participants', 'app-events'],
    show: ['show-participants', 'show-events'],
};

const attendeesTableColumns = ['company', 'profession', 'country', 'state'];

const tableColumnsMap = {
    'app-participants': attendeesTableColumns,
    'show-participants': attendeesTableColumns,
};

const chartTypes = ['line', 'bar', 'pie'];

const form = reactive(
    Nova.form({
        contentType: 'chart',
        pageTitle: 'This is the default page title',
        pageContent: '',
        type: '',
        title: '',
        table: '',
        column: '',
        height: 400,
        width: 600,
        colors: ['#1FEAA6'],
    })
);

const addColorInput = () => {
    form.colors.push('#' + Math.floor(Math.random() * 16777215).toString(16));
};

const { report, addReportPage } = inject('tool');
const { id: report_id, reportable_id, reportable_type } = report;

const reportableType = reportable_type
    .substring(reportable_type.lastIndexOf('\\') + 1, reportable_type.length)
    .toLowerCase();

const url = () =>
    `/nova-vendor/report-page-generator/reports/${report_id}/charts?reportableType=${reportable_type}&reportableId=${reportable_id}`;

const submit = () => {
    form.post(url()).then((response) => {
        addReportPage(response.data);

        emit('submited');
    });
};
</script>
