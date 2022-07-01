<template>
    <MrwFieldWrapper :label="label" :label-for="labelFor" :required="required" :error="error">
        <div class="flex relative">
            <select
                :id="labelFor"
                v-bind="{ ...$attrs }"
                :value="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
                class="w-full block form-control form-select form-select-bordered"
            >
                <option disabled value>{{ label }}</option>
                <option v-for="(option, index) in options" :key="index" v-bind="attrsFor(option)">
                    {{ option.name }}
                </option>
            </select>
            <IconArrow class="pointer-events-none form-select-arrow" />
        </div>
    </MrwFieldWrapper>
</template>

<script>
export default {
    emits: ['update:modelValue'],
    inheritAttrs: false,
    props: {
        error: { type: String, default: null },
        label: { type: String, default: 'label' },
        required: { type: Boolean, default: false },
        options: { type: Array, default: [] },
        modelValue: { type: String, default: '' },
    },

    methods: {
        attrsFor(option) {
            return {
                ...(option.attrs || {}),
                ...{ value: option.value },
            };
        },
    },
    computed: {
        labelFor() {
            return this.label.toLowerCase().replace(/\s/g, '-') + '-select-field';
        },
    },
};
</script>
