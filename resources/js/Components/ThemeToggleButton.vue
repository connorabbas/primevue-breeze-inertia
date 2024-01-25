<template>
    <Button
        :icon="currentTheme === 'lara-dark-indigo' ? 'pi pi-moon' : 'pi pi-sun'"
        @click="toggleTheme"
    ></Button>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Button from "primevue/button";
import {
    loadTheme,
    saveThemePreference,
    getSavedThemePreference,
} from "./../Modules/theme-manager.mjs";

const currentTheme = ref("lara-light-indigo"); // default theme

function toggleTheme() {
    currentTheme.value =
        currentTheme.value === "lara-dark-indigo"
            ? "lara-light-indigo"
            : "lara-dark-indigo";
    loadTheme(currentTheme.value);
    saveThemePreference(currentTheme.value);
}

onMounted(() => {
    const savedTheme = getSavedThemePreference();
    if (savedTheme !== currentTheme.value) {
        currentTheme.value = savedTheme;
        loadTheme(savedTheme);
    }
});
</script>
