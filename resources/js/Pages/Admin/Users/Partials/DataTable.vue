<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Menu from 'primevue/menu';

const props = defineProps({
    users: [Array, Object],
    paginateSize: [Number, String],
});

const selectedRowData = ref({});
const contextMenu = ref(null);
const contextMenuItems = [
    {
        label: 'Manage User',
        icon: 'pi pi-pencil',
        command: () => {
            alert('User Data: ' + JSON.stringify(selectedRowData.value));
        },
    },
];
function toggleContextMenu(event, rowData) {
    selectedRowData.value = rowData;
    if (contextMenu.value) {
        contextMenu.value.toggle(event);
    }
}

const currentRoute = route('admin.users.index');
const sortField = ref('');
const sortOrder = ref(1);
const filters = ref({
    user: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

// re-visit the current page to apply filtering/sorting/pagination
function fetchData(tableEvent) {
    const data = {};
    data.page = tableEvent.page ? tableEvent.page + 1 : 1;
    data.rows = tableEvent.rows;
    data.filter = filters.value;
    if (sortField.value) {
        data.sort = `${sortField.value}|${
            sortOrder.value === 1 ? 'asc' : 'desc'
        }`;
    }
    router.visit(currentRoute, {
        method: 'get',
        data: data,
        preserveState: true,
    });
}
function onPage(event) {
    fetchData(event);
}
function onSort(event) {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    fetchData(event);
}
function onFilter(event) {
    fetchData(event);
}
function reset() {
    router.visit(currentRoute, {
        method: 'get',
    });
}
</script>

<template>
    <Button
        type="button"
        icon="pi pi-refresh"
        label="Reset"
        class="mb-3"
        outlined
        severity="secondary"
        @click="reset()"
    />
    <Menu ref="contextMenu" class="shadow" :model="contextMenuItems" popup />
    <DataTable
        ref="dataTable"
        paginator
        showGridlines
        v-model:filters="filters"
        filterDisplay="none"
        :value="users.data"
        :rows="parseInt(paginateSize)"
        :totalRecords="users.total"
        :lazy="true"
        removableSort
        :sortField="sortField"
        :sortOrder="sortOrder"
        :rowsPerPageOptions="[10, 20, 50, 100]"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} records"
        @sort="onSort"
        @page="onPage"
        @filter="onFilter"
    >
        <Column field="name" header="Name"></Column>
        <Column field="email" header="Email"></Column>
        <Column header="Action">
            <template #body="slotProps">
                <Button
                    type="button"
                    severity="secondary"
                    text
                    rounded
                    icon="pi pi-ellipsis-v"
                    @click="toggleContextMenu($event, slotProps.data)"
                />
            </template>
        </Column>
    </DataTable>
</template>
