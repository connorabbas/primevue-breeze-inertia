<script setup>
import { ref, useTemplateRef } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useDataTable } from '@/Composables/useDataTable.js';
import { FilterMatchMode } from 'primevue/core/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Menu from 'primevue/menu';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';

const props = defineProps({
    auth: Object,
    urlParams: Object,
    purchaseOrders: [Array, Object],
});

const pageTitle = 'Purchase Orders';
const breadcrumbs = [
    { label: 'Dashboard', route: route('dashboard') },
    { label: pageTitle, route: route('purchase-orders.index') },
    { label: 'List' },
];

// Status options for dropdown filter
const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Submitted', value: 'submitted' },
    { label: 'Approved', value: 'approved' },
    { label: 'Partially Received', value: 'partially_received' },
    { label: 'Fully Received', value: 'fully_received' },
    { label: 'Closed', value: 'closed' },
    { label: 'Cancelled', value: 'cancelled' }
];

// Context menu setup
const selectedRowData = ref({});
const contextMenu = useTemplateRef('context-menu');
const contextMenuItems = [
    {
        label: 'View Details',
        icon: 'pi pi-search',
        command: () => {
            router.visit(route('purchase-orders.show', selectedRowData.value.id));
        },
    },
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
        command: () => {
            router.visit(route('purchase-orders.edit', selectedRowData.value.id));
        },
    },
    {
        label: 'Delete',
        icon: 'pi pi-trash',
        command: () => {
            // Add delete confirmation logic
            console.log('Delete PO:', selectedRowData.value.id);
        },
    },
];

function toggleContextMenu(event, rowData) {
    selectedRowData.value = rowData;
    if (contextMenu.value) {
        contextMenu.value.toggle(event);
    }
}

// DataTable setup
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
        number: { value: null, matchMode: FilterMatchMode.CONTAINS },
        supplier: { value: null, matchMode: FilterMatchMode.CONTAINS },
        status: { value: null, matchMode: FilterMatchMode.EQUALS },
        created_at: { value: null, matchMode: FilterMatchMode.DATE_IS },
        total_cost: { value: null, matchMode: FilterMatchMode.CONTAINS },
        'user.name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    },
    ['urlParams', 'purchaseOrders']
);

// Parse URL params on component mount
parseUrlParams(props.urlParams);
</script>

<template>
    <Head :title="pageTitle" />

    <AuthenticatedLayout :page-title="pageTitle" :breadcrumbs="breadcrumbs">
        <template #headerEnd>
            <div class="flex gap-2">
                <Link :href="route('purchase-orders.create')">
                    <Button
                        type="button"
                        icon="pi pi-plus"
                        label="New Order"
                        raised
                    />
                </Link>
                <Button
                    type="button"
                    icon="pi pi-filter-slash"
                    label="Clear"
                    outlined
                    @click="reset"
                />
            </div>
        </template>

        <Container :spaced-mobile="false">
            <div>
                <ResponsiveCard spacingClasses="p-3">
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
                        :value="purchaseOrders.data"
                        :totalRecords="purchaseOrders.total"
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
                        <Column
                            field="number"
                            header="PO Number"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    class="w-full"
                                    placeholder="Search by PO#"
                                    @input="filterCallback"
                                />
                            </template>
                        </Column>

                        <Column
                            field="supplier.name"
                            header="Supplier"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    class="w-full"
                                    placeholder="Search supplier"
                                    @input="filterCallback"
                                />
                            </template>
                        </Column>

                        <Column
                            field="status"
                            header="Status"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <Dropdown
                                    v-model="filterModel.value"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Status"
                                    class="w-full"
                                    @change="filterCallback"
                                />
                            </template>
                            <template #body="{ data }">
                                <Tag :severity="getStatusSeverity(data.status)">
                                    {{ data.status }}
                                </Tag>
                            </template>
                        </Column>

                        <Column
                            field="total_cost"
                            header="Total"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    class="w-full"
                                    placeholder="Search amount"
                                    @input="filterCallback"
                                />
                            </template>
                            <template #body="{ data }">
                                {{ formatCurrency(data.total_cost) }}
                            </template>
                        </Column>

                        <Column
                            field="created_at"
                            header="Created"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <Calendar
                                    v-model="filterModel.value"
                                    dateFormat="yy-mm-dd"
                                    placeholder="Search date"
                                    class="w-full"
                                    @date-select="filterCallback"
                                />
                            </template>
                            <template #body="{ data }">
                                {{ formatDate(data.created_at) }}
                            </template>
                        </Column>

                        <Column
                            field="user.name"
                            header="Created By"
                            sortable
                            :showFilterMenu="false"
                        >
                            <template #filter="{ filterModel, filterCallback }">
                                <InputText
                                    v-model="filterModel.value"
                                    type="text"
                                    class="w-full"
                                    placeholder="Search user"
                                    @input="filterCallback"
                                />
                            </template>
                        </Column>

                        <Column header="Actions" :exportable="false" style="min-width: 4rem">
                            <template #body="{ data }">
                                <Button
                                    type="button"
                                    severity="secondary"
                                    text
                                    rounded
                                    icon="pi pi-ellipsis-v"
                                    @click="toggleContextMenu($event, data)"
                                    v-tooltip.top="'Show Actions'"
                                />
                            </template>
                        </Column>
                    </DataTable>
                </ResponsiveCard>
            </div>
        </Container>
    </AuthenticatedLayout>
</template>

<script>
// Helper functions
function getStatusSeverity(status) {
    const severityMap = {
        draft: 'info',
        submitted: 'warning',
        approved: 'success',
        partially_received: 'warning',
        fully_received: 'success',
        closed: 'info',
        cancelled: 'danger'
    };
    return severityMap[status] || 'info';
}

function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}
</script>
