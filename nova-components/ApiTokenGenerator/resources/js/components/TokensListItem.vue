<template>
    <div class="token">
        <div
            class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
        >
            <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/5 md:py-5">
                <label :for="`api-token-item-${token.id}`" class="inline-block pt-2 leading-tight">
                    {{ token.name }}
                </label>
            </div>
            <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-3/5 md:py-5">
                <input
                    :id="`api-token-item-${token.id}`"
                    type="text"
                    class="w-full form-control form-input form-input-bordered mr-2"
                    :value="token.plain_text"
                    ref="tokenRef"
                    readonly
                />
            </div>
            <div class="flex items-center">
                <BasicButton type="button" @click="copyToken" class="flex-shrink-0">
                    {{ __('Copy') }}
                </BasicButton>
                <BasicButton
                    type="button"
                    @click="showDeleteConfirmation = true"
                    class="flex-shrink-0 text-red-500 items-center"
                >
                    {{ __('Delete') }}
                </BasicButton>
            </div>
        </div>
        <DeleteResourceModal
            :show="showDeleteConfirmation"
            @confirm="deleteToken"
            @close="showDeleteConfirmation = false"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    token: { type: Object },
});

const tokenRef = ref('');
const showDeleteConfirmation = ref(false);

const copyToken = () => {
    tokenRef.value.select();
    document.execCommand('copy');
};

const deleteToken = () => {
    Nova.request()
        .delete(`/nova-vendor/api-token-generator/tokens/${props.token.id}`)
        .then((res) => {
            window.location.reload();
        });
};
</script>
