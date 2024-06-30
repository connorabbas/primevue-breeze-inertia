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
        class="max-w-7xl mx-auto sm:px-6 lg:px-8"
    >
        <slot />
    </div>
</template>
