<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Container from '@/Components/Container.vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';
import LinksMenu from '@/Components/LinksMenu.vue';
import LinksMenuBar from '@/Components/LinksMenuBar.vue';
import Tag from 'primevue/tag';
import DrawerMenu from './Partials/DrawerMenu.vue';

// User menu
const logoutForm = useForm({});
const userMenu = ref(null);
const userMenuItems = [
    {
        label: 'Profile',
        route: route('admin.profile.edit'),
        icon: 'pi pi-fw pi-user',
    },
    {
        label: 'Log Out',
        icon: 'pi pi-fw pi-sign-out',
        command: () => {
            logoutForm.post(route('admin.logout'));
        },
    },
];
const toggleUserMenu = (event) => {
    userMenu.value.childRef.toggle(event);
};

// Drawer menu
const drawerOpen = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen">
            <nav
                class="bg-surface-0 dark:bg-surface-900 border-b"
                :class="
                    $slots.header
                        ? 'border-surface-100 dark:border-surface-800'
                        : 'border-surface-0 dark:border-surface-900 shadow'
                "
            >
                <!-- Primary Navigation Menu -->
                <Container>
                    <LinksMenuBar
                        :pt="{
                            root: {
                                class: 'px-0 py-3 border-0 rounded-none',
                            },
                            button: {
                                class: 'hidden',
                            },
                        }"
                    >
                        <template #start>
                            <div class="flex">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <Button
                                        text
                                        rounded
                                        severity="secondary"
                                        icon="pi pi-bars"
                                        @click="drawerOpen = true"
                                        :pt="{
                                            icon: {
                                                class: 'text-xl',
                                            },
                                        }"
                                        class="mr-5"
                                    />
                                    <Link :href="route('welcome')" class="mr-5">
                                        <ApplicationLogo
                                            class="block h-10 w-auto fill-current text-surface-900 dark:text-surface-0"
                                        />
                                    </Link>
                                    <Tag value="Primary">ADMIN</Tag>
                                </div>
                            </div>
                        </template>
                        <template #end>
                            <div class="flex items-center ms-6">
                                <ToggleThemeButton
                                    text
                                    severity="secondary"
                                    rounded
                                    :pt="{
                                        icon: {
                                            class: 'text-xl md:text-base',
                                        },
                                    }"
                                />
                                <!-- User Dropdown Menu -->
                                <div class="ms-3 relative">
                                    <LinksMenu
                                        :model="userMenuItems"
                                        popup
                                        ref="userMenu"
                                        class="shadow"
                                    />
                                    <Button
                                        class="hidden md:flex"
                                        text
                                        size="small"
                                        severity="secondary"
                                        @click="toggleUserMenu($event)"
                                    >
                                        <span class="text-base">
                                            {{ $page.props.auth.user.name }}
                                        </span>
                                        <i class="pi pi-angle-down ml-1"></i>
                                    </Button>
                                    <Button
                                        class="flex md:hidden"
                                        icon="pi pi-user"
                                        text
                                        rounded
                                        severity="secondary"
                                        :pt="{
                                            icon: {
                                                class: 'text-xl',
                                            },
                                        }"
                                        @click="toggleUserMenu($event)"
                                    />
                                </div>
                            </div>
                        </template>
                    </LinksMenuBar>
                </Container>

                <!-- Slide out drawer menu -->
                <DrawerMenu v-model="drawerOpen" />
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-surface-0 dark:bg-surface-900 shadow"
                v-if="$slots.header"
            >
                <Container>
                    <div class="py-6">
                        <slot name="header" />
                    </div>
                </Container>
            </header>

            <!-- Page Content -->
            <Toast />
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
