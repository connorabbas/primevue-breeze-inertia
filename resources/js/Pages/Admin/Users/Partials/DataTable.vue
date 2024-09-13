<script setup>
import { ref, onMounted } from 'vue';
import { useDataTable } from '@/Composables/useDataTable.js';
import { FilterMatchMode } from '@primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Menu from 'primevue/menu';

const props = defineProps({
    urlParams: Object,
    users: [Array, Object],
});

// User context menu
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

// DataTable
const {
    filters,
    sortField,
    sortOrder,
    rowsPerPage,
    firstDatasetIndex,
    onPage,
    onSort,
    onFilter,
    reset,
    parseUrlParams,
} = useDataTable(
    {
        name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
        email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    },
    ['urlParams', 'users']
);

// Parse URL params on component mount
parseUrlParams(props.urlParams);
</script>

<template>
    <div>
        <div class="flex flex-wrap justify-between items-top">
            <div>
                <h2 class="font-bold text-2xl">Users</h2>
            </div>
            <div>
                <Button
                    type="button"
                    icon="pi pi-filter-slash"
                    label="Clear"
                    class="mb-3"
                    outlined
                    severity="secondary"
                    @click="reset"
                />
            </div>
        </div>
        <Menu
            ref="contextMenu"
            class="shadow"
            :model="contextMenuItems"
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
            @sort="onSort"
            @page="onPage"
            @filter="onFilter"
        >
            <Column field="name" header="Name" sortable :showFilterMenu="false">
                <template #filter="{ filterModel, filterCallback }">
                    <InputText
                        v-model="filterModel.value"
                        type="text"
                        @input="filterCallback"
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
                        @input="filterCallback"
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
                        @click="toggleContextMenu($event, slotProps.data)"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
