<script setup>
import { ref, onMounted, onUnmounted, watchEffect, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import Menu from "primevue/menu";
import Sidebar from "primevue/sidebar";
import OuterLayoutContainer from "@/Components/OuterLayoutContainer.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ThemeToggleButton from "@/Components/ThemeToggleButton.vue";

const page = usePage();

const mainMenuItems = [
    {
        label: "Dashboard",
        href: route("dashboard"),
        isCurrentRoute: route().current("dashboard"),
    },
];
const userMenuItems = [
    {
        label: "Profile",
        href: route("profile.edit"),
        icon: "pi pi-fw pi-user",
        isCurrentRoute: route().current("profile.edit"),
    },
    {
        href: route("logout"),
        label: "Log Out",
        method: "post",
        icon: "pi pi-fw pi-sign-out",
        isCurrentRoute: route().current("logout"),
    },
];
const mobileMenuItems = [
    {
        label: "Dashboard",
        href: route("dashboard"),
        icon: "pi pi-fw pi-home",
        isCurrentRoute: route().current("dashboard"),
    },
    {
        label: "Profile",
        href: route("profile.edit"),
        icon: "pi pi-fw pi-user",
        isCurrentRoute: route().current("profile.edit"),
    },
    {
        href: route("logout"),
        label: "Log Out",
        method: "post",
        icon: "pi pi-fw pi-sign-out",
        isCurrentRoute: route().current("logout"),
    },
];

const menu = ref(null);
const mobileMenuOpen = ref(false);
const windowWidth = ref(window.innerWidth);

function toggleMenu(event) {
    menu.value.toggle(event);
}
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener("resize", updateWidth);
});
onUnmounted(() => {
    window.removeEventListener("resize", updateWidth);
});
// Watch for windowWidth changes to close sidebar are larger screens if it was opened on mobile
watchEffect(() => {
    if (windowWidth.value > 992) {
        mobileMenuOpen.value = false;
    }
});
</script>

<template>
    <div>
        <header>
            <div class="border-bottom-1 surface-border surface-overlay">
                <OuterLayoutContainer class="pb-0">
                    <Menubar
                        :model="mainMenuItems"
                        class="border-noround border-none surface-overlay px-0"
                    >
                        <template #start>
                            <Link :href="route('welcome')" class="mr-3 h-0">
                                <ApplicationLogo
                                    class="h-3rem w-auto pt-1 surface-svg-fill"
                                />
                            </Link>
                        </template>
                        <template #item="{ item, props, hasSubmenu, root }">
                            <Link
                                :href="item.href"
                                class="hidden sm:hidden md:hidden lg:flex"
                                :class="[
                                    'p-menuitem-link',
                                    item.isCurrentRoute ? 'text-primary' : '',
                                ]"
                                custom
                            >
                                <span
                                    v-show="item.icon"
                                    :class="[item.icon, 'mr-2']"
                                />
                                <span>{{ item.label }}</span>
                                <span
                                    v-if="hasSubmenu"
                                    class="pi pi-fw pi-angle-down ml-2"
                                />
                            </Link>
                        </template>
                        <template #end>
                            <div class="flex">
                                <ThemeToggleButton
                                    plain
                                    text
                                    class="inline-flex"
                                />
                                <div class="hidden sm:hidden md:hidden lg:flex">
                                    <Menu
                                        :model="userMenuItems"
                                        popup
                                        ref="menu"
                                        class="shadow-1"
                                    >
                                        <template #item="{ item, props }">
                                            <Link
                                                :href="item.href"
                                                :method="
                                                    item.method === 'post'
                                                        ? 'post'
                                                        : 'get'
                                                "
                                                :as="
                                                    item.method === 'post'
                                                        ? 'li'
                                                        : 'a'
                                                "
                                                :class="[
                                                    'p-menuitem-link',
                                                    item.method === 'post'
                                                        ? 'flex items-center w-full text-left'
                                                        : '',
                                                    item.isCurrentRoute
                                                        ? 'text-primary'
                                                        : '',
                                                ]"
                                                custom
                                            >
                                                <span
                                                    v-show="item.icon"
                                                    :class="[item.icon, 'mr-2']"
                                                />
                                                <span>{{ item.label }}</span>
                                            </Link>
                                        </template>
                                    </Menu>
                                    <Button
                                        plain
                                        text
                                        class="p-menuitem-text inline-flex"
                                        @click="toggleMenu($event)"
                                    >
                                        <span class="">{{
                                            page.props.auth.user.name
                                        }}</span>
                                        <i class="pi pi-angle-down ml-2"></i>
                                    </Button>
                                </div>
                                <Button
                                    plain
                                    text
                                    class="flex sm:flex lg:hidden xl:hidden"
                                    icon="pi pi-bars"
                                    @click="mobileMenuOpen = true"
                                />
                            </div>
                        </template>
                    </Menubar>
                </OuterLayoutContainer>
            </div>
            <!-- Mobile sidebar menu -->
            <Sidebar
                v-model:visible="mobileMenuOpen"
                header="Menu"
                position="right"
            >
                <ul class="list-none m-0 p-0">
                    <li v-for="(item, index) in mobileMenuItems" :key="index">
                        <Link
                            :href="item.href"
                            :method="item.method === 'post' ? 'post' : 'get'"
                            :as="item.method === 'post' ? 'li' : 'a'"
                            class="flex align-items-center cursor-pointer p-3 border-round text-700 hover:surface-100 transition-duration-150 transition-colors no-underline"
                            :class="[
                                item.method === 'post'
                                    ? 'flex items-center w-full text-left'
                                    : '',
                                item.isCurrentRoute ? 'text-primary' : '',
                            ]"
                            custom
                        >
                            <span v-show="item.icon" :class="[item.icon]" />
                            <span class="ml-2">{{ item.label }}</span>
                        </Link>
                    </li>
                </ul>
            </Sidebar>
        </header>
        <main>
            <slot name="header" />
            <OuterLayoutContainer :spaced-mobile="false">
                <slot />
            </OuterLayoutContainer>
        </main>
    </div>
</template>

<style scoped>
@media screen and (max-width: 992px) {
    :deep(.p-menubar .p-menubar-button) {
        display: none;
    }
}
</style>
