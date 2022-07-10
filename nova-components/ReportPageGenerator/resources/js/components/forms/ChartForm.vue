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
                label="Data query resource"
                v-model="form.queryResource"
                :error="form.getError('queryResource')"
                :options="queryResources[reportableType]"
                @update:modelValue="queryResourceChanged"
                required
            />

            <TwSelectField
                class="tw-col-span-4"
                label="Query field"
                v-model="form.queryField"
                :error="form.getError('queryField')"
                :options="queryFields"
                required
                :disabled="!form.queryResource"
            />

            <TwSelectField
                class="tw-col-span-4"
                label="Where field (optional)"
                v-model="form.whereKey"
                :error="form.getError('whereKey')"
                :options="whereKeys"
                :disabled="!form.queryResource"
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
                    :id="`dataset-label-${index}`"
                    v-model="dataset.label"
                    :error="form.getError(`datasets.${index}.label`)"
                    required
                />

                <TwSelectField
                    v-if="form.whereKey !== ''"
                    class="tw-col-span-4"
                    label="Operator"
                    :id="`where-operator-${index}`"
                    v-model="dataset.whereOperator"
                    :error="form.getError(`datasets.${index}.whereOperator`)"
                    :options="['=', '!=', '>', '<', '>=', '<=', 'like']"
                />

                <TwInputField
                    v-if="form.whereKey !== ''"
                    class="tw-col-span-4"
                    :label="form.whereKey"
                    :id="`where-value-${index}`"
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

const queryResources = {
    app: ['app-participants', 'app-events'],
    show: ['show-participants', 'show-events'],
};

const queryFields = ref([]);
const whereKeys = ref([]);

const chartTypes = ['line', 'bar', 'pie'];

const form = reactive(
    Nova.form({
        contentType: 'chart',
        pageTitle: '',
        pageContent: '',
        type: '',
        title: '',
        queryResource: '',
        queryField: '',
        whereKey: '',
        height: 400,
        width: 600,
        datasets: [
            {
                label: 'Dataset #1',
                whereValue: '',
                whereOperator: '=',
                colors: ['#5E43CC'],
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

        window.scrollTo({ top: window.outerHeight });
    });
};

const queryResourceChanged = (queryResource) => {
    Nova.request()
        .get(`/nova-vendor/report-page-generator/query-fields?queryResource=${queryResource}`)
        .then((response) => {
            queryFields.value = response.data;
            whereKeys.value = response.data;
        });
};

function genColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

watch(pageTitle, (nVal, oVal) => {
    form.pageTitle = nVal;
});
</script>
