<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="grid-nogutter min-h-full">
        <div
            class="col-span-12 md:col-span-10 md:col-start-2 lg:col-span-8 lg:col-start-3 h-full"
        >
            <div class="h-screen flex items-center justify-center">
                <div>
                    <div class="flex justify-center mb-6">
                        <ApplicationLogo
                            class="w-20 h-20 fill-current text-surface-900 dark:text-surface-0"
                        />
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
