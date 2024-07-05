<script setup>
const props = defineProps({
    spacingClasses: {
        type: String,
        required: false,
        default: 'p-4 py-6 sm:p-8',
    },
});
</script>

<template>
    <div
        class="bg-surface-0 dark:bg-surface-900 shadow rounded-none sm:rounded-xl overflow-hidden"
        :class="spacingClasses"
    >
        <slot />
    </div>
</template>
