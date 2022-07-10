<template>
    <Modal
        role="dialog"
        :show="show"
        data-testid="mrw-create-report-page-modal"
        class="tw-max-w-screen-lg"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader>{{ __('Create Report Page') }}</ModalHeader>

            <div class="tw-grid tw-grid-cols-12 tw-gap-6 p-6">
                <TwInputField
                    class="tw-col-span-6"
                    label="Page title"
                    v-model="pageTitle"
                    placeholder="Page title"
                />

                <TwSelectField
                    class="tw-col-span-6"
                    :label="__('Page content type')"
                    v-model="defaultContentType"
                    :options="contentTypes"
                />
            </div>

            <component :is="contentForms[defaultContentType]" @submited="$emit('created')">
                <template #footer="{ submit }">
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
                </template>
            </component>
        </div>
    </Modal>
</template>

<script setup>
import { provide, ref } from 'vue';
import ChartForm from '@/components/forms/ChartForm.vue';
import RichTextForm from '@/components/forms/RichTextForm.vue';

defineProps({
    show: { type: Boolean, default: false },
});

const contentForms = {
    chart: ChartForm,
    'rich-text': RichTextForm,
};

const pageTitle = ref('');
const defaultContentType = ref('rich-text');

const contentTypes = [
    { name: 'Rich Text', value: 'rich-text' },
    { name: 'Chart Data', value: 'chart' },
];

provide('newReportPage', {
    pageTitle,
    contentType: defaultContentType,
});

const emit = defineEmits(['cancel', 'created']);
</script>
