<script setup>
const props = defineProps({
    spacedMobile: {
        type: Boolean,
        default: true,
        required: false,
    },
});
</script>

<template>
    <div
        :class="[spacedMobile ? 'px-4' : 'px-0']"
        class="max-w-screen-2xl mx-auto sm:px-4"
    >
        <slot />
    </div>
</template>
