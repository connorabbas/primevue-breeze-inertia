<script setup>
defineProps({
    level: {
        type: String,
        default: 'h1',
        validator: (value) =>
            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(value),
    },
});
</script>

<template>
    <component
        :is="level"
        class="font-bold leading-tight text-black dark:text-white"
    >
        <slot />
    </component>
</template>
