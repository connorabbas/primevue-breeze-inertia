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
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
            </Link>
            <a
                v-else
                class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                :href="item.url"
                :target="item.target"
            >
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
                <span
                    v-if="item.items"
                    class="pi ml-auto"
                    :class="{
                        'pi-angle-down': !item.active,
                        'pi-angle-up': item.active,
                    }"
                />
            </a>
        </template>
    </PanelMenu>
</template>
