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
        :class="[
            'grid-nogutter',
            spacedMobile ? 'px-3' : 'px-0',
            'sm:px-3',
            'md:p-0',
            'lg:p-0',
            'xl:p-0',
        ]"
    >
        <div class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2">
            <slot />
        </div>
    </div>
</template>
