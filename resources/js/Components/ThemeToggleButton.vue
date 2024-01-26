<script setup>
import Button from "primevue/button";
import { useTheme } from "@/Composables/useTheme.js";

const { currentTheme, loadTheme } = useTheme();

/* TODO: change to `dark` and `light` values then have useTheme decide the  */
function toggleTheme() {
    const newTheme =
        currentTheme.value === "lara-light-indigo"
            ? "lara-dark-indigo"
            : "lara-light-indigo";
    loadTheme(newTheme);
}
</script>

<template>
    <Button
        title="Change theme"
        :icon="
            currentTheme === 'lara-light-indigo' ? 'pi pi-sun' : 'pi pi-moon'
        "
        @click="toggleTheme"
    />
</template>
