<script setup>
import { ref, onMounted, onUnmounted, watchEffect, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

import Menu from 'primevue/menu';
import Drawer from 'primevue/drawer';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';
import OuterLayoutContainer from '@/Components/OuterLayoutContainer.vue';

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
    if (windowWidth.value > 640) {
        mobileMenuOpen.value = false;
    }
});
</script>

<template>
    <div>
        <div class="min-h-screen">
            <nav
                class="bg-surface-0 dark:bg-surface-900 border-b border-surface"
            >
                <!-- Primary Navigation Menu -->
                <OuterLayoutContainer>
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-surface-900 dark:text-surface-0"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <ToggleThemeButton text severity="secondary" />
                            <!-- User Dropdown Menu -->
                            <div class="ms-3 relative">
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
                                            class="p-menu-item-link"
                                            :class="{
                                                'text-primary': item.isCurrentRoute,
                                                'flex items-center w-full text-left': item.method === 'post',
                                            }"
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
                                    text
                                    severity="secondary"
                                    @click="toggleUserMenu($event)"
                                >
                                    <span class="">{{
                                        $page.props.auth.user.name
                                    }}</span>
                                    <i class="pi pi-angle-down ml-2"></i>
                                </Button>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center sm:hidden">
                            <div class="relative">
                                <Button
                                    text
                                    severity="secondary"
                                    icon="pi pi-bars"
                                    @click="mobileMenuOpen = true"
                                />
                            </div>
                        </div>
                    </div>
                </OuterLayoutContainer>

                <!-- Mobile drawer menu -->
                <Drawer
                    v-model:visible="mobileMenuOpen"
                    header="Mobile Menu"
                    position="right"
                >
                </Drawer>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-surface-0 dark:bg-surface-900 shadow"
                v-if="$slots.header"
            >
                <OuterLayoutContainer>
                    <div class="py-6">
                        <slot name="header" />
                    </div>
                </OuterLayoutContainer>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
