<script setup>
import { useTemplateRef } from 'vue';

const childRef = useTemplateRef('child-ref');
defineExpose({
    childRef,
});
</script>

<template>
    <Menubar ref="child-ref" breakpoint="1024px">
        <template v-if="$slots.start" #start>
            <slot name="start"></slot>
        </template>
        <template #item="{ item, props, hasSubmenu, root }">
            <!-- add if using 'nora' preset theme -->
            <!-- hover:text-primary-100 hover:dark:text-primary-950 -->
            <Link
                v-if="item.route"
                :href="item.route"
                class="p-menubar-item-link"
                :class="{
                    'font-bold text-primary': item.active,
                    'text-muted-color': root && !item.active,
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
                :class="{
                    'text-muted-color': root,
                }"
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
                        'pi text-muted-color',
                        root ? 'pi-angle-down text-xs' : 'pi-angle-right',
                    ]"
                ></i>
            </a>
        </template>
        <template v-if="$slots.end" #end>
            <slot name="end"></slot>
        </template>
    </Menubar>
</template>
