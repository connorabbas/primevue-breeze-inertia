<script setup>
import { ref, onMounted, onUnmounted, watchEffect, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Menubar from 'primevue/menubar';
import Menu from 'primevue/menu';
import Drawer from 'primevue/drawer';
import OuterLayoutContainer from '@/Components/OuterLayoutContainer.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeToggleButton from '@/Components/ThemeToggleButton.vue';

const mainMenuItems = [
    {
        label: 'Dashboard',
        href: route('dashboard'),
        isCurrentRoute: route().current('dashboard'),
    },
];
const userMenuItems = [
    {
        label: 'Profile',
        href: route('profile.edit'),
        icon: 'pi pi-fw pi-user',
        isCurrentRoute: route().current('profile.edit'),
    },
    {
        href: route('logout'),
        label: 'Log Out',
        method: 'post',
        icon: 'pi pi-fw pi-sign-out',
        isCurrentRoute: route().current('logout'),
    },
];
const mobileMenuItems = [
    {
        label: 'Dashboard',
        href: route('dashboard'),
        icon: 'pi pi-fw pi-home',
        isCurrentRoute: route().current('dashboard'),
    },
    {
        label: 'Profile',
        href: route('profile.edit'),
        icon: 'pi pi-fw pi-user',
        isCurrentRoute: route().current('profile.edit'),
    },
    {
        href: route('logout'),
        label: 'Log Out',
        method: 'post',
        icon: 'pi pi-fw pi-sign-out',
        isCurrentRoute: route().current('logout'),
    },
];

const menu = ref(null);
const mobileMenuOpen = ref(false);
const windowWidth = ref(window.innerWidth);

function toggleUserMenu(event) {
    menu.value.toggle(event);
}
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener('resize', updateWidth);
});
onUnmounted(() => {
    window.removeEventListener('resize', updateWidth);
});
// Watch for windowWidth changes to close drawer on larger screens if it was opened on mobile
watchEffect(() => {
    if (windowWidth.value > 992) {
        mobileMenuOpen.value = false;
    }
});
</script>

<template>
    <div class="min-h-full">
        <nav>
            <div
                class="border-b border-surface bg-surface-0 dark:bg-surface-900"
            >
                <OuterLayoutContainer class="pb-0">
                    <Menubar
                        :model="mainMenuItems"
                        class="rounded-none border-0 bg-surface-0 dark:bg-surface-900 py-3 px-0"
                    >
                        <template #start>
                            <Link :href="route('welcome')" class="mr-8">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-surface-900 dark:text-surface-0"
                                />
                            </Link>
                        </template>
                        <template #item="{ item, props, hasSubmenu, root }">
                            <Link
                                :href="item.href"
                                class="hidden sm:flex p-menubar-item-link text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-0 transition !duration-150"
                                :class="{
                                    '!text-primary': item.isCurrentRoute,
                                }"
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
                                    class="inline-flex text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-0 transition !duration-150"
                                />
                                <div class="hidden sm:flex">
                                    <Menu
                                        :model="userMenuItems"
                                        popup
                                        ref="menu"
                                        class="shadow"
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
                                                    'p-menu-item-link',
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
                                                    :class="[item.icon, 'mr-1']"
                                                />
                                                <span>{{ item.label }}</span>
                                            </Link>
                                        </template>
                                    </Menu>
                                    <Button
                                        plain
                                        text
                                        class="inline-flex text-surface-500 dark:text-surface-400 hover:text-surface-700 dark:hover:text-surface-0 transition !duration-150"
                                        @click="toggleUserMenu($event)"
                                    >
                                        <span class="">{{
                                            $page.props.auth.user.name
                                        }}</span>
                                        <i class="pi pi-angle-down ml-2"></i>
                                    </Button>
                                </div>
                                <Button
                                    plain
                                    text
                                    class="inline-flex sm:hidden"
                                    icon="pi pi-bars"
                                    @click="mobileMenuOpen = true"
                                />
                            </div>
                        </template>
                    </Menubar>
                </OuterLayoutContainer>
            </div>
            <!-- Mobile drawer menu -->
            <Drawer
                v-model:visible="mobileMenuOpen"
                header="Mobile Menu"
                position="right"
            >
                <ul class="list-none m-0 p-0">
                    <li v-for="(item, index) in mobileMenuItems" :key="index">
                        <Link
                            :href="item.href"
                            :method="item.method === 'post' ? 'post' : 'get'"
                            :as="item.method === 'post' ? 'li' : 'a'"
                            class="flex items-center cursor-pointer p-4 rounded-border text-surface-700 dark:text-surface-100 hover:bg-surface-100 dark:hover:bg-surface-700 duration-150 transition-colors no-underline"
                            :class="[
                                item.method === 'post'
                                    ? 'flex items-center w-full text-left'
                                    : '',
                                item.isCurrentRoute ? '!text-primary' : '',
                            ]"
                            custom
                        >
                            <span v-show="item.icon" :class="[item.icon]" />
                            <span class="ml-2">{{ item.label }}</span>
                        </Link>
                    </li>
                </ul>
            </Drawer>
        </nav>
        <header
            class="bg-surface-0 dark:bg-surface-900 py-7 shadow"
            v-if="$slots.header"
        >
            <OuterLayoutContainer>
                <slot name="header" />
            </OuterLayoutContainer>
        </header>
        <main>
            <slot />
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
