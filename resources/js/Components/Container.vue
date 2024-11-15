<script setup>
const props = defineProps({
    spacedMobile: {
        type: Boolean,
        default: true,
        required: false,
    },
    fluid: {
        type: Boolean,
        default: true,
        required: false,
    },
});
</script>

<template>
    <div
        :class="[
            { 'max-w-screen-2xl mx-auto': !fluid },
            spacedMobile ? 'px-4' : 'px-0',
            'md:px-8',
        ]"
    >
        <slot />
    </div>
</template>
