<script setup>
import { ref } from 'vue';
import Menu from 'primevue/menu';

const childRef = ref(null);

defineExpose({
    childRef,
});
</script>

<template>
    <Menu ref="childRef">
        <template #item="{ item, props }">
            <Link
                v-if="item.route"
                :href="item.route"
                class="p-menu-item-link"
                custom
            >
                <span v-show="item.icon" :class="item.icon" class="mr-1" />
                <span>{{ item.label }}</span>
            </Link>
            <a
                v-else
                :href="item.url"
                :target="item.target"
                v-bind="props.action"
            >
                <span v-show="item.icon" :class="item.icon" class="mr-1" />
                <span>{{ item.label }}</span>
            </a>
        </template>
    </Menu>
</template>
