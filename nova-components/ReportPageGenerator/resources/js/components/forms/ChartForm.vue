<template>
    <form>
        <div
            class="tw-grid tw-grid-cols-12 tw-gap-6 p-6 border-t border-gray-100 dark:border-gray-700"
        >
            <div class="tw-col-span-12">
                <h4 class="tw-text-lg tw-leading-none tw-font-semibold">Chart Configuration</h4>
            </div>
            <TwInputField
                class="tw-col-span-12"
                label="Chart title"
                v-model="form.title"
                :error="form.getError('title')"
                placeholder="Chart title"
                required
            />

            <TwSelectField
                class="tw-col-span-4"
                label="Chart data source"
                v-model="form.table"
                :error="form.getError('table')"
                :options="tables[reportableType]"
                required
            />

            <TwSelectField
                v-if="tableColumnsMap[form.table]"
                class="tw-col-span-4"
                label="Data field"
                v-model="form.column"
                :error="form.getError('column')"
                :options="tableColumnsMap[form.table]"
                required
            />

            <TwInputField
                v-else
                class="tw-col-span-4"
                label="Data field"
                v-model="form.column"
                :error="form.getError('column')"
                placeholder="Ex: video, page, ect."
                required
            />

            <TwInputField
                class="tw-col-span-4"
                label="Where field (optional)"
                v-model="form.whereKey"
                :error="form.getError('whereKey')"
                placeholder="Some condition to be met"
            />

            <TwSelectField
                class="tw-col-span-4"
                label="Chart type"
                v-model="form.type"
                :error="form.getError('type')"
                :options="chartTypes"
                required
            />

            <TwInputField
                class="tw-col-span-4"
                label="Chart height"
                type="number"
                v-model="form.height"
                :error="form.getError('height')"
            />

            <TwInputField
                class="tw-col-span-4"
                label="Chart width"
                type="number"
                v-model="form.width"
                :error="form.getError('width')"
            />
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
            <h4 class="tw-text-lg tw-leading-none tw-font-semibold">Datasets Configuration</h4>
            <div
                class="tw-grid tw-grid-cols-12 tw-gap-6 tw-py-6 border-b border-gray-100 dark:border-gray-700 tw-relative"
                v-for="(dataset, index) in form.datasets"
                :key="index"
            >
                <button
                    type="button"
                    v-if="form.datasets.length > 1"
                    @click="deleteDataset(index)"
                    class="absolute text-red-400 tw-top-4 tw-right-4"
                >
                    <Icon type="trash" />
                </button>

                <TwInputField
                    class="tw-col-span-4"
                    label="Dataset label"
                    v-model="dataset.label"
                    :error="form.getError(`datasets.${index}.label`)"
                    required
                />

                <TwSelectField
                    v-if="form.whereKey !== ''"
                    class="tw-col-span-4"
                    label="Operator"
                    v-model="dataset.whereOperator"
                    :error="form.getError(`datasets.${index}.whereOperator`)"
                    :options="['=', '!=', '>', '<', '>=', '<=', 'like']"
                />

                <TwInputField
                    v-if="form.whereKey !== ''"
                    class="tw-col-span-4"
                    :label="form.whereKey"
                    v-model="dataset.whereValue"
                    :error="form.getError(`datasets.${index}.whereValue`)"
                />

                <div class="tw-col-span-12">
                    <h6 class="tw-mb-2">Dataset colors</h6>
                    <div class="tw-grid tw-grid-cols-12 tw-gap-6">
                        <div
                            v-for="(color, index) in dataset.colors"
                            :key="index"
                            class="tw-col-span-2 tw-flex tw-rounded bg-gray-100 dark:bg-gray-700"
                        >
                            <input
                                type="color"
                                v-model="dataset.colors[index]"
                                class="w-full px-2 tw-h-9 tw-bg-transparent"
                            />
                            <button
                                v-if="dataset.colors.length > 1"
                                type="button"
                                class="tw-h-9 tw-pr-2 tw-flex tw-items-center tw-justify-center"
                                @click="deleteColorInput(dataset, index)"
                            >
                                <Icon type="x" />
                            </button>
                        </div>
                        <div v-if="form.type === 'pie'" class="tw-col-span-2">
                            <OutlineButton
                                type="button"
                                @click="addColorInput(dataset)"
                                class="w-full"
                            >
                                <Icon type="plus" />
                            </OutlineButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-mt-6">
                <OutlineButton type="button" @click="addDataset" class="w-full">
                    <Icon type="plus" />
                    <span>Add Dataset</span>
                </OutlineButton>
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
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
import { reactive, inject, ref, watch } from 'vue';

const emit = defineEmits(['submited']);

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
        pageTitle: '',
        pageContent: '',
        type: '',
        title: '',
        table: '',
        column: '',
        whereKey: '',
        height: 400,
        width: 600,
        datasets: [
            {
                label: 'Dataset #1',
                whereValue: '',
                whereOperator: '=',
                colors: ['#1FEAA6'],
            },
        ],
    })
);

const addDataset = () => {
    form.datasets.push({
        label: 'Dataset #' + (form.datasets.length + 1),
        whereValue: '',
        whereOperator: '=',
        colors: [genColor()],
    });
};

const deleteDataset = (datasetIndex) => {
    form.datasets = form.datasets.filter((dataset, index) => index != datasetIndex);
};

const addColorInput = (dataset) => {
    dataset.colors.push(genColor());
};

const deleteColorInput = (dataset, colorIndex) => {
    dataset.colors = dataset.colors.filter((color, index) => index != colorIndex);
};

const { pageTitle } = inject('newReportPage');
const { report, addReportPage } = inject('tool');
const { id: report_id, reportable_id, reportable_type } = report;

const reportableType = reportable_type
    .substring(reportable_type.lastIndexOf('\\') + 1, reportable_type.length)
    .toLowerCase();

const url = () =>
    `/nova-vendor/report-page-generator/reports/${report_id}/charts?reportableType=${reportable_type}&reportableId=${reportable_id}`;

const submit = () => {
    form.post(url(), { preserveSate: true }).then((response) => {
        addReportPage(response.data);

        emit('submited');
    });
};

function genColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

watch(pageTitle, (nVal, oVal) => {
    form.pageTitle = nVal;
});
</script>
