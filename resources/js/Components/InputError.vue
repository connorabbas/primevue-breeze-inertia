<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="m-0 text-sm text-red-500">
            {{ message }}
        </p>
    </div>
</template>
