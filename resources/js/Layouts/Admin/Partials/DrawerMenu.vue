<script setup>
import { ref, onMounted, onUnmounted, watchEffect } from 'vue';
import Drawer from 'primevue/drawer';
import LinksPanelMenu from '@/Components/LinksPanelMenu.vue';

// Menu Items
const currentRoute = route().current();
const homeMenuItems = ref([
    {
        label: 'Welcome',
        icon: 'pi pi-home',
        route: route('welcome'),
        active: currentRoute == 'welcome',
    },
    {
        label: 'Dashboard',
        icon: 'pi pi-th-large',
        route: route('admin.dashboard'),
        active: currentRoute == 'admin.dashboard',
    },
]);
const exampleNestedMenuItems = ref([
    {
        label: 'Files',
        icon: 'pi pi-file',
        items: [
            {
                label: 'Images',
                icon: 'pi pi-image',
                items: [
                    {
                        label: 'Logos',
                        icon: 'pi pi-image',
                    },
                ],
            },
        ],
    },
    {
        label: 'Cloud',
        icon: 'pi pi-cloud',
        items: [
            {
                label: 'Upload',
                icon: 'pi pi-cloud-upload',
                command: () => {
                    alert('Example using programmatic functionality');
                }
            },
            {
                label: 'Download',
                icon: 'pi pi-cloud-download',
            },
            {
                label: 'Sync',
                icon: 'pi pi-refresh',
            },
        ],
    },
]);

// Drawer behavior
const model = defineModel();
const menuBackdrop = ref(false);
const windowWidth = ref(window.innerWidth);
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};
onMounted(() => {
    window.addEventListener('resize', updateWidth);
});
onUnmounted(() => {
    window.removeEventListener('resize', updateWidth);
});
watchEffect(() => {
    if (windowWidth.value <= 768) {
        menuBackdrop.value = true;
    } else {
        menuBackdrop.value = false;
    }
});
</script>

<template>
    <Drawer
        v-model:visible="model"
        position="left"
        :modal="menuBackdrop"
        :showCloseIcon="menuBackdrop"
    >
        <div>
            <div class="mb-5">
                <p class="text-muted-color font-bold uppercase text-sm mb-2">
                    Home
                </p>
                <LinksPanelMenu :model="homeMenuItems" class="w-full" />
            </div>
            <div class="mb-5">
                <p class="text-muted-color font-bold uppercase text-sm mb-2">
                    Example Nested
                </p>
                <LinksPanelMenu
                    :model="exampleNestedMenuItems"
                    class="w-full"
                />
            </div>
        </div>
    </Drawer>
</template>
