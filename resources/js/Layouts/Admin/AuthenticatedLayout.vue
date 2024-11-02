<script setup>
import { ref, useTemplateRef } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Container from '@/Components/Container.vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';
import LinksBreadcrumb from '@/Components/PrimeVue/LinksBreadcrumb.vue';
import LinksMenu from '@/Components/PrimeVue/LinksMenu.vue';
import LinksMenuBar from '@/Components/PrimeVue/LinksMenuBar.vue';
import DrawerMenu from './Partials/DrawerMenu.vue';

const props = defineProps({
    pageTitle: {
        required: false,
        type: String,
    },
    breadcrumbs: {
        required: false,
        type: Array,
        default: () => [],
    },
});

// User menu
const logoutForm = useForm({});
const userMenu = useTemplateRef('user-menu');
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
    <div class="h-screen flex flex-col">
        <!-- Navigation Menu -->
        <nav
            class="bg-surface-0 dark:bg-surface-900 border-b mb-8"
            :class="
                $slots.header
                    ? 'border-surface-100 dark:border-surface-800'
                    : 'border-surface-0 dark:border-surface-900 shadow'
            "
        >
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
                                    outlined
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
                                    ref="user-menu"
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
            <DrawerMenu v-model="drawerOpen" />
        </nav>

        <!-- Page Heading -->
        <header v-if="pageTitle" class="mb-6">
            <Container>
                <div class="flex items-end justify-between flex-wrap">
                    <div>
                        <LinksBreadcrumb
                            v-if="breadcrumbs.length"
                            :model="breadcrumbs"
                            class="mb-4"
                        />
                        <h1
                            class="font-bold text-2xl sm:text-3xl leading-tight"
                        >
                            {{ pageTitle }}
                        </h1>
                    </div>
                    <div>
                        <div v-if="$slots.headerEnd">
                            <slot name="headerEnd" />
                        </div>
                    </div>
                </div>
            </Container>
        </header>

        <!-- Page Content -->
        <main class="grow">
            <Toast />
            <slot />
        </main>

        <!-- Footer -->
        <footer
            class="w-full mt-10 border-t border-surface-100 dark:border-surface-800"
        >
            <div
                class="flex justify-center bg-surface-0 dark:bg-surface-900 py-6"
            >
                <p class="text-muted-color">
                    Your Company - {{ new Date().getFullYear() }}
                </p>
            </div>
        </footer>
    </div>
</template>
