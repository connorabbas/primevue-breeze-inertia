<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';
import Toast from 'primevue/toast';
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo
                    class="w-20 h-20 fill-current text-surface-900 dark:text-surface-0"
                />
            </Link>
        </div>

        <div v-if="$slots.message" class="w-full sm:max-w-md mt-6 px-4 sm:px-0">
            <slot name="message" />
        </div>

        <div class="w-full sm:max-w-md mt-6">
            <Toast />
            <ResponsiveCard>
                <slot />
            </ResponsiveCard>
        </div>
    </div>
</template>
