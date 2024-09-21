<script setup>
import PanelMenu from 'primevue/panelmenu';
</script>

<template>
    <PanelMenu
        :pt="{
            root: {
                class: 'p-0 m-0 gap-1',
            },
            panel: {
                class: 'p-0 border-0',
            },
            header: {
                class: 'p-0 border-0',
            },
            itemContent: {
                class: 'gap-1',
            },
        }"
    >
        <template #item="{ item }">
            <Link
                v-if="item.route"
                :href="item.route"
                custom
                class="flex items-center cursor-pointer px-4 py-2"
                :class="
                    item.active
                        ? 'text-primary'
                        : 'text-surface-700 dark:text-surface-0'
                "
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-panelmenu-item-icon mr-2"
                />
                <span>{{ item.label }}</span>
            </Link>
            <a
                v-else
                class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                :href="item.url"
                :target="item.target"
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-panelmenu-item-icon mr-2"
                />
                <span>{{ item.label }}</span>
                <span v-if="item.items" class="pi pi-angle-down ml-auto" />
            </a>
        </template>
    </PanelMenu>
</template>
