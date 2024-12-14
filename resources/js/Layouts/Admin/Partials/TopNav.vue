<script setup>
import { useTemplateRef } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ToggleThemeButton from '@/Components/ToggleThemeButton.vue';
import LinksMenu from '@/Components/PrimeVue/LinksMenu.vue';
import LinksMenuBar from '@/Components/PrimeVue/LinksMenuBar.vue';

const emit = defineEmits(['open-nav']);

// User menu
const logoutForm = useForm({});
const userMenu = useTemplateRef('user-menu');
const userMenuItems = [
    {
        label: 'Profile',
        url: route('admin.profile.edit'),
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
</script>

<template>
    <nav class="dynamic-bg border-b dynamic-border">
        <Container :fluid="true">
            <LinksMenuBar
                :pt="{
                    root: {
                        class: 'px-0 py-3 border-0 rounded-none dynamic-bg',
                    },
                    button: {
                        class: 'hidden',
                    },
                }"
            >
                <template #start>
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <Button
                                class="flex lg:hidden mr-4"
                                outlined
                                severity="secondary"
                                icon="pi pi-bars"
                                @click="emit('open-nav')"
                                :pt="{
                                    icon: {
                                        class: 'text-xl',
                                    },
                                }"
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
                    <div class="flex items-center space-x-3">
                        <div>
                            <ToggleThemeButton
                                text
                                severity="secondary"
                                rounded
                            />
                        </div>
                        <!-- User Dropdown Menu -->
                        <div class="flex flex-col">
                            <Button
                                id="user-menu-btn"
                                text
                                severity="secondary"
                                :label="$page.props.auth.user.name"
                                class="hidden sm:flex"
                                icon="pi pi-angle-down"
                                iconPos="right"
                                @click="toggleUserMenu($event)"
                            />
                            <Button
                                class="flex sm:hidden"
                                icon="pi pi-user"
                                text
                                rounded
                                severity="secondary"
                                @click="toggleUserMenu($event)"
                            />
                            <div id="user-menu-append" class="relative"></div>
                            <LinksMenu
                                appendTo="#user-menu-append"
                                :model="userMenuItems"
                                popup
                                ref="user-menu"
                                :pt="{
                                    root: {
                                        class: '!left-auto !top-0 right-0',
                                    },
                                }"
                            />
                        </div>
                    </div>
                </template>
            </LinksMenuBar>
        </Container>
    </nav>
</template>
