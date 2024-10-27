<script setup>
import { ref, useTemplateRef } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useLazyDataTable } from '@/Composables/useLazyDataTable.js';
import { FilterMatchMode } from '@primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Menu from 'primevue/menu';
import AuthenticatedAdminLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';

const props = defineProps({
    auth: Object,
    users: Object,
});

const pageTitle = 'Users';
const breadcrumbs = [
    { label: 'Dashboard', route: route('admin.dashboard') },
    { label: pageTitle, route: route('admin.users.index') },
    { label: 'List' },
];

// User context menu
const userContextMenu = useTemplateRef('user-context-menu');
const userContextMenuItems = ref([]);
function toggleUserContextMenu(event, userData) {
    console.log(userData);
    // Populate menu items based on row
    userContextMenuItems.value = [
        {
            label: 'Manage User',
            icon: 'pi pi-pencil',
            command: () => {
                alert('User Data: ' + JSON.stringify(userData));
            },
        },
    ];
    // Show the menu
    userContextMenu.value.toggle(event);
}

// DataTable
const {
    filters,
    sortField,
    sortOrder,
    rowsPerPage,
    firstDatasetIndex,
    hasFilteringApplied,
    debounceInputFilter,
    onFilter,
    onSort,
    onPage,
    fetchData,
    resetTable,
} = useLazyDataTable(
    {
        name: { value: null, matchMode: FilterMatchMode.CONTAINS },
        email: { value: null, matchMode: FilterMatchMode.CONTAINS },
    },
    ['users']
);
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedAdminLayout
        :page-title="pageTitle"
        :breadcrumbs="breadcrumbs"
    >
        <template #headerEnd>
            <Button
                v-if="hasFilteringApplied"
                severity="secondary"
                type="button"
                icon="pi pi-filter-slash"
                label="Clear Filters"
                outlined
                @click="resetTable"
            />
        </template>

        <Container :spaced-mobile="false">
            <div>
                <ResponsiveCard spacingClasses="p-3">
                    <Menu
                        ref="user-context-menu"
                        class="shadow"
                        :model="userContextMenuItems"
                        popup
                    />
                    <DataTable
                        ref="dataTable"
                        lazy
                        paginator
                        stripedRows
                        showGridlines
                        removableSort
                        resizableColumns
                        columnResizeMode="fit"
                        :value="users.data"
                        :totalRecords="users.total"
                        v-model:filters="filters"
                        filterDisplay="row"
                        :sortField="sortField"
                        :sortOrder="sortOrder"
                        :rows="rowsPerPage"
                        :rowsPerPageOptions="[10, 20, 50, 100]"
                        :first="firstDatasetIndex"
                        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} records"
                        @filter="onFilter"
                        @sort="onSort"
                        @page="onPage"
                    >
                        <Column
                            field="name"
                            header="Name"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="debounceInputFilter(filterCallback)"
                                    class="w-full"
                                    placeholder="Search by name"
                                />
                            </template>
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column
                            field="email"
                            header="Email"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    @input="debounceInputFilter(filterCallback)"
                                    class="w-full"
                                    placeholder="Search by Email"
                                />
                            </template>
                            <template #body="slotProps">
                                {{ slotProps.data.email }}
                            </template>
                        </Column>
                        <Column header="Action">
                            <template #body="slotProps">
                                <Button
                                    type="button"
                                    severity="secondary"
                                    text
                                    rounded
                                    icon="pi pi-ellipsis-v"
                                    @click="
                                        toggleUserContextMenu(
                                            $event,
                                            slotProps.data
                                        )
                                    "
                                    v-tooltip.top="'Show User Actions'"
                                />
                            </template>
                        </Column>
                    </DataTable>
                </ResponsiveCard>
            </div>
        </Container>
    </AuthenticatedAdminLayout>
</template>
