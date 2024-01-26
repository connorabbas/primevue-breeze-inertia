<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div class="grid-nogutter h-full">
        <div
            class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2 h-full"
        >
            <div
                class="h-screen flex align-items-center justify-content-center"
            >
                <div>
                    <div class="flex justify-content-center mb-4">
                        <ApplicationLogo
                            class="h-5rem w-auto pt-1 surface-svg-fill"
                        />
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
