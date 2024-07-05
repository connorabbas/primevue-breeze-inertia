<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-500 dark:text-red-300">
            {{ message }}
        </p>
    </div>
</template>
