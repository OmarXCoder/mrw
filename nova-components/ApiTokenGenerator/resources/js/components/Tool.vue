<template>
    <div>
        <div class="flex flex-row mb-6">
            <h2 class="text-2xl w-full">Api Access Tokens</h2>
            <button
                class="shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0"
                dusk="create-button"
                @click="showModal = true"
            >
                <span class="inline-block">{{ __('Generate New Token') }}</span>
            </button>
        </div>
        <Modal role="dialog" :show="showModal" data-testid="generate-token-modal">
            <form class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <ModalHeader>{{ __('Generate Token') }}</ModalHeader>

                <div
                    class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
                >
                    <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/5 md:py-5">
                        <label for="token-name-field" class="inline-block pt-2 leading-tight">
                            {{ __('Name') }}
                            <span class="text-red-500 text-sm">*</span>
                        </label>
                    </div>
                    <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-3/5 md:py-5">
                        <input
                            id="token-name-field"
                            type="text"
                            placeholder="Name"
                            class="w-full form-control form-input form-input-bordered"
                            v-model="newTokenName"
                        />
                        <HelpText class="mt-2 help-text-error" v-if="nameError && showNameError">
                            {{ nameError }}
                        </HelpText>
                    </div>
                </div>

                <ModalFooter>
                    <div class="flex items-center ml-auto">
                        <LinkButton @click="closeModal" class="mr-3">{{ __('Cancel') }}</LinkButton>
                        <DefaultButton type="button" @click="generateToken">
                            {{ __('Generate') }}
                        </DefaultButton>
                    </div>
                </ModalFooter>
            </form>
        </Modal>

        <TokensList
            :tokens="tokens"
            @asked-to-generate-token="showModal = true"
            @token-regenerated="handleTokenRegenerated"
            @token-deleted="handleTokenDeleted"
        />
    </div>
</template>

<script setup>
import TokensList from './TokensList.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    resourceName: String,
    resourceId: Number,
    panel: Object,
});

const tokens = ref([]);
const showModal = ref(false);
const newTokenName = ref('');
const nameError = ref('Name is required');
const showNameError = ref(false);

const url = () =>
    `/nova-vendor/api-token-generator?resourceName=${props.resourceName}&resourceId=${props.resourceId}`;

const generateToken = () => {
    if (!newTokenName.value) {
        showNameError.value = true;
        return false;
    } else {
        Nova.request()
            .post(url(), { name: newTokenName.value })
            .then((res) => {
                showNameError.value = false;
                newTokenName.value = '';
                showModal.value = false;
                tokens.value.unshift(res.data);
            });
    }
};

const closeModal = () => {
    newTokenName.value = '';
    showNameError.value = false;
    showModal.value = false;
};

const handleTokenRegenerated = (oldToken, newToken) => {
    tokens.value.splice(tokens.value.indexOf(oldToken), 1, newToken);
};

const handleTokenDeleted = (token) => {
    tokens.value.splice(tokens.value.indexOf(token), 1);
};

onMounted(() => {
    Nova.request()
        .get(url())
        .then((res) => {
            tokens.value = res.data;
        });
});
</script>
