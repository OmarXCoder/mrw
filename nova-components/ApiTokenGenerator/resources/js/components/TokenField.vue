<template>
    <div class="token">
        <div class="flex items-center mb-4">
            <input
                type="text"
                class="w-full form-control form-input form-input-bordered mr-2"
                :value="token.plain_text"
                ref="tokenRef"
                readonly
            />
            <BasicButton type="button" @click="copyToken" class="flex-shrink-0">
                {{ __('Copy') }}
            </BasicButton>
            <BasicButton type="button" @click="deleteToken" class="flex-shrink-0">
                {{ __('Delete') }}
            </BasicButton>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    token: { type: Object },
});

const tokenRef = ref('');

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
