<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary-400 dark:border-primary-600 font-medium leading-5 text-surface-900 dark:text-surface-100 focus:outline-none focus:border-primary-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent font-medium leading-5 text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-300 hover:border-surface-300 dark:hover:border-surface-700 focus:outline-none focus:text-surface-700 dark:focus:text-surface-300 focus:border-surface-300 dark:focus:border-surface-700 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
