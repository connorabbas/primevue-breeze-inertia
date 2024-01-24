<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="grid-nogutter h-full">
        <div class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2 h-full">
            <slot />
        </div>
    </div>
</template>
