<template>
    <Modal
        role="dialog"
        :show="show"
        data-testid="mrw-create-report-page-modal"
        max-width="screen-md"
    >
        <form class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader>{{ __('Create Report Page') }}</ModalHeader>

            <InputField
                :label="__('Heading')"
                v-model="form.heading"
                :error="form.getError('heading')"
                required
            />

            <SelectField
                :label="__('Chart')"
                v-model="form.chart"
                :error="form.getError('chart')"
                :options="chartOptions"
                required
            />

            <SelectField
                :label="__('Chart Type')"
                v-model="form.type"
                :error="form.getError('type')"
                :options="chartTypes"
                required
            />

            <InputField
                :label="__('Chart Color')"
                v-model="form.color"
                :error="form.getError('color')"
                required
                type="color"
            />

            <FieldWrapper
                :label="__('Content')"
                for-attr="trix-content-filed"
                :error="form.getError('content')"
            >
                <Trix
                    name="trix-content-filed"
                    :value="form.content"
                    :with-files="false"
                    @change="(value) => (form.content = value)"
                    class="min-h-40"
                />
            </FieldWrapper>

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
        color: '',
        chart: '',
        heading: '',
        content: '',
    })
);

const { report, addReportPage } = inject('tool');
const { id: report_id, reportable_id, reportable_type } = report;

const chartOptions = [
    { name: 'Participants By Country', value: 'participants-by-country' },
    { name: 'Participants By Company', value: 'participants-by-company' },
    { name: 'Participants By Profession', value: 'participants-by-profession' },
];

const chartTypes = [
    { name: 'Line Chart', value: 'line-chart' },
    { name: 'Bar Chart', value: 'bar-chart' },
    { name: 'Pie Chart', value: 'pie-chart' },
];

const url = () =>
    `/nova-vendor/report-page-generator/reports/${report_id}/pages?reportableType=${reportable_type}&reportableId=${reportable_id}`;

const submit = () => {
    form.post(url()).then((response) => {
        addReportPage(response.data);

        emit('created');
    });
};
</script>
