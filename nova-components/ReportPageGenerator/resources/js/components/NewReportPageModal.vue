<template>
    <Modal
        role="dialog"
        :show="show"
        data-testid="mrw-create-report-page-modal"
        max-width="screen-md"
    >
        <form class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader>{{ __('Create Report Page') }}</ModalHeader>

            <div
                class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
            >
                <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/3 md:py-5">
                    <label for="heading-field" class="inline-block pt-2 leading-tight">
                        {{ __('Heading') }}
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                </div>
                <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-2/3 md:py-5">
                    <input
                        id="heading-field"
                        type="text"
                        placeholder="Name"
                        class="w-full form-control form-input form-input-bordered"
                        v-model="form.heading"
                    />
                    <HelpText class="mt-2 help-text-error" v-if="form.errors.has('heading')">
                        {{ form.getError('heading') }}
                    </HelpText>
                </div>
            </div>

            <div
                class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
            >
                <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/3 md:py-5">
                    <label for="chart-field" class="inline-block pt-2 leading-tight">
                        {{ __('Chart') }}
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                </div>
                <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-2/3 md:py-5">
                    <select
                        id="chart-field"
                        v-model="form.chart"
                        class="w-full block form-control form-select form-select-bordered"
                    >
                        <option selected disabled value>Select Chart</option>
                        <option value="participants-by-country">Participant By Country</option>
                        <option value="participants-by-company">Participant By Company</option>
                        <option value="participants-by-profession">
                            Participant By Profession
                        </option>
                    </select>
                    <HelpText class="mt-2 help-text-error" v-if="form.errors.has('chart')">
                        {{ form.getError('chart') }}
                    </HelpText>
                </div>
            </div>

            <div
                class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
            >
                <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/3 md:py-5">
                    <label for="type-field" class="inline-block pt-2 leading-tight">
                        {{ __('Chart Type') }}
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                </div>
                <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-2/3 md:py-5">
                    <select
                        id="type-field"
                        v-model="form.type"
                        class="w-full block form-control form-select form-select-bordered"
                    >
                        <option selected disabled value>Select Chart Type</option>
                        <option value="line-chart">Line Chart</option>
                        <option value="bar-chart">Bar Chart</option>
                        <option value="pie-chart">Pie Chart</option>
                    </select>
                    <HelpText class="mt-2 help-text-error" v-if="form.errors.has('type')">
                        {{ form.getError('type') }}
                    </HelpText>
                </div>
            </div>

            <div class="field-wrapper border-b border-gray-100 dark:border-gray-700">
                <div class="px-6 md:px-8">
                    <label for="content-field" class="inline-block pt-2 leading-tight">
                        {{ __('Content') }}
                    </label>
                </div>
                <div class="mt-3 pb-5 px-6 md:px-8">
                    <Trix
                        name="trixman"
                        placeholder="notes"
                        :value="form.content"
                        :with-files="false"
                        @change="trixChange"
                        class="min-h-40"
                    />
                </div>
            </div>

            <ModalFooter>
                <div class="flex items-center ml-auto">
                    <LinkButton @click="$emit('cancel')" class="mr-3">
                        {{ __('Cancel') }}
                    </LinkButton>
                    <DefaultButton type="button" @click.prevent="submit">
                        {{ __('Create Page') }}
                    </DefaultButton>
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script setup>
import { reactive, inject } from 'vue';

defineProps({
    show: { type: Boolean, default: false },
});

const emit = defineEmits(['cancel', 'created']);

const form = reactive(
    Nova.form({
        type: '',
        chart: '',
        heading: '',
        content: '',
    })
);

const reportId = inject('reportId');
const { addReportPage } = inject('reportPages');
const toolMeta = inject('toolMeta');

const url = () =>
    `/nova-vendor/report-page-generator/reports/${reportId}/pages?reportableType=${toolMeta.reportable_type}&reportableId=${toolMeta.reportable_id}`;

const submit = () => {
    form.post(url()).then((response) => {
        addReportPage(response.data);

        emit('created');
    });
};

const trixChange = (value) => {
    form.content = value;
};
</script>

<style></style>
