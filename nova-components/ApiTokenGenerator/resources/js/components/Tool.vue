<template>
    <div>
        <div class="flex flex-row">
            <h2 class="text-2xl w-full">Api Access Tokens</h2>
            <button
                class="flex-shrink-0 shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0"
                dusk="create-button"
                @click="generateToken"
            >
                <span class="inline-block">Generate Token</span>
            </button>
        </div>
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow mt-3 py-2 px-6"
            v-if="tokens.length > 0"
        >
            <div
                class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0"
                v-for="token in tokens"
                :key="token.id"
            >
                <div class="md:w-1/4 md:py-3 px-8">
                    <h4 class="font-bold md:font-normal text-right">
                        <span>{{ token.name }}</span>
                    </h4>
                </div>
                <div class="md:w-3/4 md:py-3 break-all lg:break-words">
                    <TokenField :token="token" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TokenField from './TokenField.vue';

export default {
    props: ['resourceName', 'resourceId', 'panel'],
    components: { TokenField },
    data() {
        return {
            tokens: [],
        };
    },
    mounted() {
        Nova.request()
            .get(this.url())
            .then((res) => {
                this.tokens = res.data;
            });
    },
    methods: {
        generateToken() {
            Nova.request()
                .post(this.url())
                .then((res) => {
                    window.location.reload();
                });
        },
        url() {
            return `/nova-vendor/api-token-generator?resourceName=${this.$props.resourceName}&resourceId=${this.$props.resourceId}`;
        },
    },
};
</script>
