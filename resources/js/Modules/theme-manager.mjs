export function loadTheme(themeName) {
    const themeLink =
        document.getElementById("theme-css") || document.createElement("link");
    themeLink.id = "theme-css";
    themeLink.rel = "stylesheet";
    themeLink.href = `/themes/${themeName}/theme.css`;

    if (!document.getElementById("theme-css")) {
        document.head.appendChild(themeLink);
    }
}

export function saveThemePreference(themeName) {
    localStorage.setItem("selectedTheme", themeName);
}

export function getSavedThemePreference() {
    return localStorage.getItem("selectedTheme") || "lara-light-indigo";
}
