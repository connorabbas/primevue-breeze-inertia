<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import Menubar from "primevue/menubar";
import Button from "primevue/button";
import Menu from "primevue/menu";
import { ref } from "vue";

const page = usePage();
const mainMenuItems = [
    {
        intertiaLink: true,
        label: "Dashboard",
        href: route("dashboard"),
        isCurrentRoute: route().current("dashboard"),
    },
    // TODO: profile dropdown
    /* {
        label: page.props.auth.user.name,
        items: [
            {
                label: "Components",
                icon: "pi pi-bolt",
            },
        ],
    }, */
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

const menu = ref(null);

function toggleMenu(event) {
    menu.value.toggle(event);
}
</script>

<template>
    <div>
        <header>
            <div class="border-bottom-1 border-200 bg-white">
                <div class="grid-nogutter">
                    <div
                        class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2 pb-0"
                    >
                        <Menubar
                            :model="mainMenuItems"
                            class="border-noround border-none bg-white px-0"
                        >
                            <template #start>
                                <div class="mr-4">
                                    <b>LOGO</b>
                                </div>
                            </template>
                            <template #item="{ item, props, hasSubmenu, root }">
                                <Link
                                    v-if="item.intertiaLink"
                                    :href="item.href"
                                    :class="[
                                        'p-menuitem-link',
                                        item.isCurrentRoute
                                            ? 'text-primary'
                                            : '',
                                    ]"
                                    custom
                                >
                                    <span
                                        v-show="item.icon"
                                        :class="[item.icon]"
                                    />
                                    <span class="ml-2">{{ item.label }}</span>
                                </Link>
                                <a
                                    v-else
                                    :href="item.url"
                                    :target="item.target"
                                    v-bind="props.action"
                                >
                                    <span :class="item.icon" />
                                    <span class="ml-2">{{ item.label }}</span>
                                    <span
                                        v-if="hasSubmenu"
                                        class="pi pi-fw pi-angle-down ml-2"
                                    />
                                </a>
                            </template>
                            <template #end>
                                <div class="hidden md:block">
                                    <Menu
                                        :model="userMenuItems"
                                        popup
                                        ref="menu"
                                        class="shadow-2"
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
                                                    :class="[item.icon]"
                                                />
                                                <span class="ml-2">{{
                                                    item.label
                                                }}</span>
                                            </Link>
                                        </template>
                                    </Menu>
                                    <Button
                                        plain
                                        text
                                        class="p-menuitem-text"
                                        @click="toggleMenu($event)"
                                    >
                                        <span class="">{{
                                            page.props.auth.user.name
                                        }}</span>
                                        <i class="pi pi-angle-down ml-2"></i>
                                    </Button>
                                </div>
                            </template>
                        </Menubar>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <slot name="header" />
            <div class="grid-nogutter">
                <div
                    class="col-12 md:col-10 md:col-offset-1 lg:col-8 lg:col-offset-2"
                >
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<style></style>
