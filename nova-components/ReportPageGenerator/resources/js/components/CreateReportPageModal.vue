<template>
    <Modal
        role="dialog"
        :show="show"
        data-testid="mrw-create-report-page-modal"
        class="tw-max-w-screen-lg"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader>{{ __('Create Report Page') }}</ModalHeader>

            <MrwSelectField
                :label="__('Page Content Type')"
                v-model="pageType"
                :options="pageTypes"
                required
            />

            <component :is="components[pageType]" @submited="$emit('created')">
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
import { reactive, inject, ref } from 'vue';
import ChartForm from '@/components/forms/ChartForm.vue';
import RichTextForm from '@/components/forms/RichTextForm.vue';

defineProps({
    show: { type: Boolean, default: false },
});

const components = {
    chart: ChartForm,
    'rich-text': RichTextForm,
};

const pageType = ref('chart');

const pageTypes = [
    { name: 'Rich Text', value: 'rich-text' },
    { name: 'Chart Data', value: 'chart' },
];

const emit = defineEmits(['cancel', 'created']);
</script>
