<script setup>
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="flex items-center cursor-pointer p-4 rounded-lg text-surface-700 hover:bg-surface-100 dark:text-surface-0 dark:hover:bg-surface-800 duration-150 transition-colors"
        :class="{ '!text-primary': active }"
    >
        <slot />
    </Link>
</template>
