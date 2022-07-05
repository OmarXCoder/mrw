<template>
    <div>
        <h1 v-if="page.title" class="text-2xl tw-mb-6 tw-leading-none">{{ page.title }}</h1>

        <div v-if="page.content" class="plain-html" v-html="page.content"></div>
    </div>
</template>

<script setup>
const props = defineProps({
    page: { type: Object, default: {} },
});
</script>
