<script setup>
import { ref, useTemplateRef, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import LinksBreadcrumb from '@/Components/PrimeVue/LinksBreadcrumb.vue';
import MobileSidebarNavDrawer from './Partials/MobileSidebarNavDrawer.vue';
import TopNav from './Partials/TopNav.vue';
import Footer from './Partials/Footer.vue';
import SideMenuItems from './Partials/SideMenuItems.vue';

const props = defineProps({
    pageTitle: {
        type: String,
        required: false,
    },
    breadcrumbs: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const page = usePage();

// Drawer menus
const navDrawerOpen = ref(false);

// Dynamic header height for arbitrary class styling
const headerHeight = ref('');
const siteHeader = useTemplateRef('site-header');
onMounted(() => {
    if (siteHeader.value) {
        headerHeight.value = `${siteHeader.value.offsetHeight}px`;
    }
});
</script>

<template>
    <Teleport to="body">
        <Toast position="top-center" />
        <MobileSidebarNavDrawer v-model="navDrawerOpen">
            <SideMenuItems />
        </MobileSidebarNavDrawer>
    </Teleport>

    <div class="h-screen flex flex-col">
        <header
            id="site-header"
            ref="site-header"
            class="block lg:fixed top-0 left-0 right-0 z-50"
        >
            <!-- Main Nav -->
            <TopNav @open-nav="navDrawerOpen = true" />
        </header>

        <main class="flex-1">
            <!-- Desktop Sidebar -->
            <aside
                :class="[
                    'w-[18rem] inset-0 hidden lg:block fixed overflow-y-auto overflow-x-hidden dynamic-bg border-r dynamic-border',
                    `top-[${headerHeight}]`,
                ]"
            >
                <div class="w-full px-8 py-6">
                    <SideMenuItems />
                </div>
            </aside>

            <!-- Scrollable Content -->
            <div
                :class="[
                    'flex flex-col h-full lg:pl-[18rem]',
                    `lg:pt-[${headerHeight}]`,
                ]"
            >
                <!-- Breadcrumbs Nav -->
                <nav
                    v-if="breadcrumbs.length"
                    class="dynamic-bg border-b dynamic-border"
                >
                    <Container :fluid="true">
                        <div
                            class="flex items-center justify-between flex-wrap"
                        >
                            <div>
                                <LinksBreadcrumb
                                    :model="breadcrumbs"
                                    class="py-3 lg:py-5"
                                />
                            </div>
                            <div>
                                <div v-if="$slots.headerEnd" class="py-3">
                                    <slot name="headerEnd" />
                                </div>
                            </div>
                        </div>
                    </Container>
                </nav>

                <!-- Page Title -->
                <section v-if="pageTitle">
                    <Container :fluid="true" class="my-4 md:mt-8 md:mb-6">
                        <div class="flex items-end justify-between flex-wrap">
                            <div>
                                <h1
                                    class="font-bold text-2xl md:text-3xl leading-tight"
                                >
                                    {{ pageTitle }}
                                </h1>
                            </div>
                            <div>
                                <div v-if="$slots.titleEnd">
                                    <slot name="titleEnd" />
                                </div>
                            </div>
                        </div>
                    </Container>
                </section>

                <!-- Page Content -->
                <section id="page-content" class="grow">
                    <slot />
                </section>

                <footer class="">
                    <Footer />
                </footer>
            </div>
        </main>
    </div>
</template>
