<template>
    <div
        class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row"
    >
        <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/5 md:py-5">
            <label :for="labelFor" class="inline-block pt-2 leading-tight">
                {{ label }}
                <span v-if="required" class="text-red-500 text-sm">*</span>
            </label>
        </div>
        <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 w-full md:w-4/5 md:py-5">
            <slot />
            <HelpText class="mt-2 help-text-error" v-if="error">
                {{ error }}
            </HelpText>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        required: { type: Boolean, default: false },
        labelFor: String,
        error: { type: String, default: null },
    },
};
</script>
