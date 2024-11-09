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
        <template #item="{ item, active }">
            <!-- add if using 'nora' preset theme -->
            <!-- hover:text-primary-100 hover:dark:text-primary-950 -->
            <Link
                v-if="item.route"
                :href="item.route"
                custom
                class="flex items-center cursor-pointer no-underline px-4 py-2"
                :class="
                    item.active
                        ? 'font-bold text-primary'
                        : 'text-surface-700 dark:text-surface-0'
                "
            >
                <i
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-panelmenu-item-icon mr-2"
                />
                <span>{{ item.label }}</span>
            </Link>
            <a
                v-else
                class="flex items-center cursor-pointer no-underline text-surface-700 dark:text-surface-0 px-4 py-2"
                :href="item.url"
                :target="item.target"
            >
                <i
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-panelmenu-item-icon mr-2"
                />
                <span>{{ item.label }}</span>
                <span
                    v-if="item.items"
                    :class="[
                        'pi text-muted-color ml-auto',
                        active ? 'pi-angle-down' : 'pi-angle-right',
                    ]"
                />
            </a>
        </template>
    </PanelMenu>
</template>
