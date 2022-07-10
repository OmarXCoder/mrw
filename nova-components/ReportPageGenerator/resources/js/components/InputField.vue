<template>
    <div :class="$attrs.class">
        <label :for="labelFor" class="inline-block leading-tight mb-2">
            <span>{{ label }}</span>
            <span v-if="required" class="tw-text-red-500 tw-text-sm">*</span>
        </label>
        <input
            :id="labelFor"
            :type="type"
            class="form-input form-input-bordered"
            :class="{ 'form-control w-full': type != 'color' }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="defaultAttributes"
        />
        <div class="mt-2 tw-text-red-500" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
<script>
import { kebabCase, omit } from 'lodash';

export default {
    emits: ['update:modelValue'],
    inheritAttrs: false,
    props: {
        modelValue: { type: [String, Number] },
        type: { type: String, default: 'text' },
        id: { type: String, default: null },
        label: { type: String, default: 'label' },
        error: { type: String, default: null },
        required: { type: Boolean, default: false },
    },
    computed: {
        labelFor() {
            return this.id ? this.id : kebabCase(this.label) + '-input-field';
        },
        defaultAttributes() {
            return omit(this.$attrs, ['class']);
        },
    },
};
</script>
