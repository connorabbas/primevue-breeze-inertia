<script setup>
import Breadcrumb from 'primevue/breadcrumb';
</script>

<template>
    <Breadcrumb
        :pt="{
            root: {
                class: 'p-0 bg-transparent',
            },
        }"
        :home="{
            icon: 'pi pi-home',
            route: route('welcome'),
        }"
    >
        <template #item="{ item, props }">
            <Link
                v-if="item.route"
                :href="item.route"
                class="p-breadcrumb-item-link"
                custom
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-breadcrumb-item-icon"
                />
                <span class="p-breadcrumb-item-label">{{ item.label }}</span>
            </Link>
            <a
                v-else
                :href="item.url"
                :target="item.target"
                v-bind="props.action"
            >
                <span
                    v-show="item.icon"
                    :class="item.icon"
                    class="p-breadcrumb-item-icon"
                />
                <span class="p-breadcrumb-item-label">{{ item.label }}</span>
            </a>
        </template>
        <template #separator> / </template>
    </Breadcrumb>
</template>
