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
            spacedMobile ? 'px-4' : 'px-0',
            'sm:px-4',
            'md:p-0',
            'lg:p-0',
            'xl:p-0',
        ]"
    >
        <div class="col-span-12 md:col-span-10 md:col-start-2 lg:col-span-8 lg:col-start-3">
            <slot />
        </div>
    </div>
</template>
