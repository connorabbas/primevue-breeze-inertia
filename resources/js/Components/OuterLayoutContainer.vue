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
        class="container mx-auto sm:px-4"
    >
        <slot />
    </div>
</template>
