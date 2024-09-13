<script setup>
import { ref } from 'vue';
import Menubar from 'primevue/menubar';

const childRef = ref(null);

defineExpose({
    childRef,
});
</script>

<template>
    <Menubar ref="childRef">
        <template v-if="$slots.start" #start>
            <slot name="start"></slot>
        </template>
        <template #item="{ item, props, hasSubmenu, root }">
            <Link
                v-if="item.route"
                :href="item.route"
                class="p-menubar-item-link"
                :class="{
                    'font-bold text-primary dark:text-primary-300 bg-primary-50 dark:bg-primary-950 rounded-lg':
                        item.active,
                }"
                custom
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-menu-item-icon"
                />
                <span class="p-menu-item-label">{{ item.label }}</span>
            </Link>
            <a
                v-else
                :href="item.url"
                :target="item.target"
                v-bind="props.action"
                class="p-menubar-item-link"
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-menu-item-icon"
                />
                <span class="p-menu-item-label">{{ item.label }}</span>
                <i
                    v-if="hasSubmenu"
                    :class="[
                        'pi pi-angle-down',
                        {
                            'pi-angle-down ml-2': root,
                            'pi-angle-right ml-auto': !root,
                        },
                    ]"
                ></i>
            </a>
        </template>
        <template v-if="$slots.end" #end>
            <slot name="end"></slot>
        </template>
    </Menubar>
</template>
