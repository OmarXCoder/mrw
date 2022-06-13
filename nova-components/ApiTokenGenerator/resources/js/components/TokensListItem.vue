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
                <BasicButton type="button" @click="copyToken" v-tooltip="'Copy Token'">
                    <Icon
                        class="text-gray-500 dark:text-gray-400"
                        :solid="true"
                        type="clipboard"
                        width="24"
                        height="24"
                    />
                </BasicButton>

                <BasicButton type="button" @click="regenerateToken" v-tooltip="'Refresh Token'">
                    <Icon
                        class="text-gray-500 dark:text-gray-400"
                        :solid="true"
                        type="refresh"
                        width="24"
                        height="24"
                    />
                </BasicButton>

                <BasicButton
                    type="button"
                    @click="showDeleteConfirmation = true"
                    v-tooltip="'Delete Token'"
                >
                    <Icon
                        class="text-red-500 dark:text-red-400"
                        :solid="true"
                        type="trash"
                        width="24"
                        height="24"
                    />
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

const regenerateToken = () => {
    Nova.request()
        .patch(`/nova-vendor/api-token-generator/tokens/${props.token.id}`)
        .then((res) => {
            window.location.reload();
        });
};

const deleteToken = () => {
    Nova.request()
        .delete(`/nova-vendor/api-token-generator/tokens/${props.token.id}`)
        .then((res) => {
            window.location.reload();
        });
};
</script>
