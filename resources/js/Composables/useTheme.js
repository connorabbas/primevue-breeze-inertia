import { ref } from "vue";

const lightTheme = "lara-light-indigo";
const darkTheme = "lara-dark-indigo";
const currentTheme = ref(getSavedThemePreference());

function setTheme(theme) {
    const themeLink =
        document.getElementById("theme-css") || document.createElement("link");

    if (theme == "dark") {
        document.body.classList.add("dark-theme");
    } else {
        document.body.classList.remove("dark-theme");
    }

    var themeName = theme == "light" ? lightTheme : darkTheme;
    themeLink.id = "theme-css";
    themeLink.rel = "stylesheet";
    themeLink.href = `/themes/${themeName}/theme.css`;

    if (!document.getElementById("theme-css")) {
        document.head.appendChild(themeLink);
    }

    saveThemePreference(theme);
}

function saveThemePreference(theme) {
    localStorage.setItem("selectedTheme", theme);
    currentTheme.value = theme;
}

function getSavedThemePreference() {
    return localStorage.getItem("selectedTheme") || lightTheme;
}

export function useTheme() {
    return { currentTheme, setTheme, getSavedThemePreference };
}
