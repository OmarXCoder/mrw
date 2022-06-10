<template>
    <Modal role="dialog" :show="show" :data-testid="data.modal">
        <form class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader>{{ __('Token Generated') }}</ModalHeader>

            <ModalContent>
                <div class="action">
                    <div class="flex items-center mb-4">
                        <input
                            type="text"
                            class="w-full form-control form-input form-input-bordered mr-2"
                            :value="data.token"
                            ref="tokenRef"
                        />
                        <BasicButton type="button" @click="copyToken">
                            {{ __('Copy') }}
                        </BasicButton>
                    </div>
                </div>
                <div
                    class="
                        p-4
                        rounded
                        bg-yellow-100
                        text-yellow-600
                        dark:bg-yellow-300 dark:text-yellow-800
                    "
                >
                    {{
                        __(
                            "Make sure to copy your new api access token now. You won't be able to see it again!"
                        )
                    }}
                </div>
            </ModalContent>

            <ModalFooter>
                <div class="flex items-center ml-auto">
                    <DefaultButton @click="$emit('close')">{{ __('Close') }}</DefaultButton>
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    data: { type: Object },
});

defineEmits(['close']);

const tokenRef = ref('');

const copyToken = () => {
    tokenRef.value.select();
    document.execCommand('copy');
};

onMounted(() => {
    tokenRef.value.focus();
});
</script>