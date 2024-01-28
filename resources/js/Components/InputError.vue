<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="m-0 text-sm p-error">
            {{ message }}
        </p>
    </div>
</template>
