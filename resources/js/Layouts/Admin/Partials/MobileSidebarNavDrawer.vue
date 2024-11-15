<script setup>
import { usePage } from '@inertiajs/vue3';

const model = defineModel();
const page = usePage();
</script>

<template>
    <Drawer
        v-model:visible="model"
        :header="page.props.app.name"
        position="left"
    >
        <slot />
    </Drawer>
</template>
