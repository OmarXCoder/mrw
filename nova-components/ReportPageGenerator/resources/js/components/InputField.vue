<template>
    <MrwFieldWrapper :label="label" :label-for="labelFor" :required="required" :error="error">
        <input
            :id="labelFor"
            :type="type"
            class="form-input form-input-bordered"
            :class="{ 'form-control w-full': type != 'color' }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="{ ...$attrs }"
            :placeholder="label"
        />
    </MrwFieldWrapper>
</template>
<script>
export default {
    emits: ['update:modelValue'],
    inheritAttrs: false,
    props: {
        modelValue: String,
        type: { type: String, default: 'text' },
        label: { type: String, default: 'label' },
        error: { type: String, default: null },
        required: { type: Boolean, default: false },
    },
    computed: {
        labelFor() {
            return this.label.toLowerCase().replace(/\s/g, '-') + '-input-field';
        },
    },
};
</script>
