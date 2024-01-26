import { ref, watch, computed } from "vue";

const defaultTheme = "lara-light-indigo";
const currentTheme = ref(getSavedThemePreference());

const logoFillClass = computed(() => {
    return currentTheme.value == defaultTheme
        ? "svg-fill-light"
        : "svg-fill-dark";
});
watch(currentTheme, (newTheme) => {
    saveThemePreference(newTheme);
});

function loadTheme(themeName) {
    const themeLink =
        document.getElementById("theme-css") || document.createElement("link");
    themeLink.id = "theme-css";
    themeLink.rel = "stylesheet";
    themeLink.href = `/themes/${themeName}/theme.css`;

    if (!document.getElementById("theme-css")) {
        document.head.appendChild(themeLink);
    }

    currentTheme.value = themeName;
}

function saveThemePreference(themeName) {
    localStorage.setItem("selectedTheme", themeName);
    currentTheme.value = themeName;
}

function getSavedThemePreference() {
    return localStorage.getItem("selectedTheme") || defaultTheme;
}

export function useTheme() {
    return { currentTheme, loadTheme, logoFillClass };
}
