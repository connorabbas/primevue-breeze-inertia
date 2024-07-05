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
    <!-- swap max-w-7xl with container if desired... -->
    <div
        :class="[spacedMobile ? 'px-4' : 'px-0']"
        class="max-w-7xl mx-auto sm:px-4 lg:px-8"
    >
        <slot />
    </div>
</template>
