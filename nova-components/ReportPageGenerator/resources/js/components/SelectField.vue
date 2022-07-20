<template>
    <div :class="$attrs.class">
        <label :for="labelFor" class="inline-block leading-tight mb-2">
            <span>{{ label }}</span>
            <span v-if="required" class="tw-text-red-500 tw-text-sm">*</span>
        </label>
        <div class="flex relative">
            <select
                :id="labelFor"
                v-bind="{ ...$attrs }"
                :value="modelValue"
                @change="$emit('update:modelValue', $event.target.value)"
                class="w-full block form-control form-select form-select-bordered"
                :class="{ 'tw-py-3': multiple }"
                :multiple="multiple"
            >
                <option selected value>Select None</option>
                <option
                    v-for="(option, index) in normalizedOptions"
                    :key="index"
                    v-bind="attrsFor(option)"
                >
                    {{ option.name }}
                </option>
            </select>
            <IconArrow v-if="!multiple" class="pointer-events-none form-select-arrow" />
        </div>
        <div class="mt-2 tw-text-red-500" v-if="error">
            {{ error }}
        </div>
    </div>
</template>

<script>
import { capitalize, kebabCase, map, isArray } from 'lodash';

export default {
    emits: ['update:modelValue'],
    inheritAttrs: false,
    props: {
        error: { type: String, default: null },
        id: { type: String, default: null },
        label: { type: String, default: 'label' },
        required: { type: Boolean, default: false },
        multiple: { type: Boolean, default: false },
        options: { type: [Array, Object], default: [] },
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
            return this.id ? this.id : kebabCase(this.label) + '-select-field';
        },
        defaultAttributes() {
            return omit(this.$attrs, ['class']);
        },
        normalizedOptions() {
            if (isArray(this.options)) {
                return this.options.map((option) =>
                    typeof option === 'string'
                        ? { value: option, name: capitalize(option).replace(/[-_]/g, ' ') }
                        : option
                );
            }
            return map(this.options, (name, value) => ({ name, value }));
        },
    },
};
</script>
