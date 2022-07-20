<template>
    <form>
        <div
            class="tw-grid tw-grid-cols-12 tw-gap-6 p-6 border-t border-gray-100 dark:border-gray-700"
        >
            <div class="tw-col-span-12">
                <h4 class="tw-text-lg tw-leading-none tw-font-semibold">Chart Configuration</h4>
            </div>
            <!-- Chart Title -->
            <TwInputField
                class="tw-col-span-6"
                label="Chart title"
                v-model="form.title"
                :error="form.getError('title')"
                placeholder="Chart title"
                required
            />
            <!-- Query Resource -->
            <TwSelectField
                class="tw-col-span-6"
                label="Data resource"
                v-model="form.queryResource"
                :error="form.getError('queryResource')"
                :options="queryResources"
                @update:modelValue="handleQueryResourceChange"
                required
            />

            <!-- Apps -->
            <div class="tw-col-span-12">
                <VueMultiselect
                    v-model="form.selectedApps"
                    :options="showAppsList"
                    :multiple="true"
                    :close-on-select="true"
                    :searchable="false"
                    :taggable="true"
                    :preselect-first="true"
                    track-by="value"
                    label="name"
                    placeholder="Select apps"
                >
                </VueMultiselect>
            </div>

            <div v-if="form.queryResource" class="tw-grid tw-grid-cols-12 tw-gap-6 tw-col-span-12">
                <!-- Event Type -->
                <TwSelectField
                    v-if="'events' === form.queryResource"
                    class="tw-col-span-3"
                    label="Event type"
                    v-model="form.eventCode"
                    :error="form.getError('eventCode')"
                    :options="eventTypes"
                    @update:modelValue="handleEventTypeChange"
                    required
                />
                <!-- Action Type -->
                <TwSelectField
                    v-if="'events' === form.queryResource"
                    class="tw-col-span-3"
                    label="Action type"
                    v-model="form.actionCode"
                    :error="form.getError('actionCode')"
                    :options="actionTypes"
                    :disabled="actionTypes.length == 0"
                    @update:modelValue="handleActionTypeChange"
                    required
                />
                <!-- Query Field -->
                <TwSelectField
                    class="tw-col-span-3"
                    label="Query field"
                    v-model="form.queryField"
                    :error="form.getError('queryField')"
                    :options="queryFields"
                    required
                    :disabled="queryFields.length == 0"
                />
                <!-- Where Key -->
                <TwSelectField
                    class="tw-col-span-3"
                    label="Where (optional)"
                    v-model="form.whereKey"
                    :error="form.getError('whereKey')"
                    :options="whereKeyOptions"
                    :disabled="whereKeyOptions.length == 0"
                    @update:modelValue="handleWhereKeyChange"
                />
            </div>

            <div v-if="form.queryResource" class="tw-grid tw-grid-cols-12 tw-gap-6 tw-col-span-12">
                <!-- Chart Type -->
                <TwSelectField
                    class="tw-col-span-4"
                    label="Chart type"
                    v-model="form.type"
                    :error="form.getError('type')"
                    :options="chartTypes"
                    required
                />
                <!-- Chart Height -->
                <TwInputField
                    class="tw-col-span-4"
                    label="Chart height"
                    type="number"
                    v-model="form.height"
                    :error="form.getError('height')"
                />
                <!-- Chart Width -->
                <TwInputField
                    class="tw-col-span-4"
                    label="Chart width"
                    type="number"
                    v-model="form.width"
                    :error="form.getError('width')"
                />
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
            <h4 class="tw-text-lg tw-leading-none tw-font-semibold">Datasets Configuration</h4>
            <div
                class="tw-grid tw-grid-cols-12 tw-gap-6 tw-pt-6 tw-relative"
                v-for="(dataset, index) in form.datasets"
                :key="index"
                :class="{
                    'border-b border-gray-100 dark:border-gray-700 tw-pb-6':
                        index != form.datasets.length - 1,
                }"
            >
                <button
                    type="button"
                    v-if="form.datasets.length > 1"
                    @click="deleteDataset(index)"
                    class="absolute text-red-400 tw-top-2 tw-right-0"
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
                    v-if="form.whereKey"
                    class="tw-col-span-4"
                    label="Operator"
                    :id="`where-operator-${index}`"
                    v-model="dataset.whereOperator"
                    :error="form.getError(`datasets.${index}.whereOperator`)"
                    :options="whereOperatorOptions"
                    required
                />

                <TwSelectField
                    v-if="form.whereKey"
                    class="tw-col-span-4"
                    :label="form.whereKey"
                    :id="`where-value-${index}`"
                    v-model="dataset.whereValue"
                    :error="form.getError(`datasets.${index}.whereValue`)"
                    :options="whereValueOptions"
                    :disabled="whereValueOptions.length == 0"
                    required
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

            <div
                v-if="form.whereKey"
                class="tw-pt-6 tw-mt-6 border-t border-gray-100 dark:border-gray-700"
            >
                <OutlineButton type="button" @click="addDataset" class="w-full">
                    <Icon type="plus" />
                    <span>Add Dataset</span>
                </OutlineButton>
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-700" id="chart-form-content">
            <label for="content" class="inline-block leading-tight mb-2">Notes</label>
            <Trix
                id="chart-form-content-trix"
                name="chart-form-content-filed"
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
import { reactive, inject, ref, watch, computed, onMounted } from 'vue';
import VueMultiselect from 'vue-multiselect';

const emit = defineEmits(['submited']);

// Injects
const { pageTitle } = inject('newReportPage');
const { baseUrl, report, addReportPage } = inject('tool');
const { id: reportId, reportableId, reportableType } = report;

const eventTypes = ref([]);
const actionTypes = ref([]);
const queryFields = ref([]);
const whereKeyOptions = ref([]);
const whereValueOptions = ref([]);
const showAppsList = ref([]);
const queryResources = ['attendees', 'events'];
const chartTypes = ['line', 'bar', 'pie'];

const form = reactive(
    Nova.form({
        contentType: 'chart',
        pageTitle: '',
        pageContent: '',
        title: '',
        type: 'bar',
        queryResource: '',
        eventCode: null,
        actionCode: null,
        queryField: null,
        whereKey: null,
        selectedApps: [],
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

const whereOperatorOptions = computed(() =>
    form.whereKey === 'app_id' ? ['=', '!='] : ['=', '!=', '>', '<', '>=', '<=']
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

function handleQueryResourceChange(queryResource) {
    queryFields.value = [];
    whereKeyOptions.value = [];
    form.whereKey = null;

    if ('attendees' === queryResource) {
        fetchQueryFields();
    } else {
        fetchEventTypes();
    }
}

function handleEventTypeChange(eventCode) {
    queryFields.value = [];
    actionTypes.value = [];
    form.whereKey = null;
    form.queryField = null;
    form.actionCode = null;

    if (!eventCode) {
        return;
    }

    fetchActionTypes(eventCode);

    fetchQueryFields();
}

function handleActionTypeChange(actionCode) {
    queryFields.value = [];
    form.whereKey = null;
    form.queryField = null;

    if (!actionCode) {
        return;
    }

    fetchQueryFields();
}

function handleWhereKeyChange(field) {
    whereValueOptions.value = [];

    if (!field) {
        form.datasets = [form.datasets[0]];
        return;
    }

    Nova.request()
        .get(
            `${baseUrl}/field-values?queryResource=${form.queryResource}&eventCode=${form.eventCode}&actionCode=${form.actionCode}&field=${field}&reportableType=${reportableType}&reportableId=${reportableId}`
        )
        .then((response) => {
            whereValueOptions.value = response.data;
        });
}

function fetchEventTypes() {
    Nova.request()
        .get(`${baseUrl}/event-types?reportableType=${reportableType}&reportableId=${reportableId}`)
        .then((response) => {
            eventTypes.value = response.data;
        });
}

function fetchActionTypes(eventCode) {
    return Nova.request()
        .get(
            `${baseUrl}/action-types?eventCode=${eventCode}&reportableType=${reportableType}&reportableId=${reportableId}`
        )
        .then((response) => {
            actionTypes.value = response.data;
        });
}

function fetchQueryFields() {
    Nova.request()
        .get(
            `${baseUrl}/query-fields?queryResource=${form.queryResource}&eventCode=${form.eventCode}&actionCode=${form.actionCode}&reportableType=${reportableType}&reportableId=${reportableId}`
        )
        .then((response) => {
            whereKeyOptions.value = response.data;
            queryFields.value = response.data;
        });
}

function genColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

function submit() {
    if (form.selectedApps.length > 0) {
        form.selectedApps = form.selectedApps.map((item) => item.value);
    }

    form.post(
        `${baseUrl}/reports/${reportId}/charts?reportableType=${reportableType}&reportableId=${reportableId}`,
        { preserveSate: true }
    ).then((response) => {
        addReportPage(response.data);

        emit('submited');
    });
}

watch(pageTitle, (nVal, oVal) => {
    form.pageTitle = nVal;
});

onMounted(() => {
    if (reportableType === 'show') {
        Nova.request()
            .get(`${baseUrl}/shows/${reportableId}/apps`)
            .then((response) => {
                showAppsList.value = response.data;
            });
    }
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
