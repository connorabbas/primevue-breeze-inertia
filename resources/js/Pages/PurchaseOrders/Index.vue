<script setup>
import { ref, useTemplateRef } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { useDataTable } from '@/Composables/useDataTable.js';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Menu from 'primevue/menu';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Container from '@/Components/Container.vue';
import ResponsiveCard from '@/Components/ResponsiveCard.vue';

// Define FilterMatchMode as a frozen constant
const FilterMatchMode = Object.freeze({
    STARTS_WITH: 'startsWith',
    CONTAINS: 'contains',
    NOT_CONTAINS: 'notContains',
    ENDS_WITH: 'endsWith',
    EQUALS: 'equals',
    DATE_IS: 'dateIs',
});

const props = defineProps({
    auth: Object,
    urlParams: Object,
    purchaseOrders: {
        type: Object,
        required: true,
        default: () => ({ data: [], total: 0 })
    }
});

// Initial filters setup with proper nested object handling
const initialFilters = {
    number: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'supplier.name': { value: null, matchMode: FilterMatchMode.CONTAINS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
    created_at: { value: null, matchMode: FilterMatchMode.DATE_IS },
    total_cost: { value: null, matchMode: FilterMatchMode.CONTAINS },
    'user.name': { value: null, matchMode: FilterMatchMode.CONTAINS },
};

// DataTable state management
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
} = useDataTable(initialFilters, ['urlParams', 'purchaseOrders']);

// Status options
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
const selectedRowData = ref(null);
const contextMenu = useTemplateRef('context-menu');
const contextMenuItems = [
    {
        label: 'View Details',
        icon: 'pi pi-search',
        command: () => {
            if (selectedRowData.value?.id) {
                router.visit(route('purchase-orders.show', selectedRowData.value.id));
            }
        },
    },
    {
        label: 'Edit',
        icon: 'pi pi-pencil',
        command: () => {
            if (selectedRowData.value?.id) {
                router.visit(route('purchase-orders.edit', selectedRowData.value.id));
            }
        },
    },
    {
        label: 'Delete',
        icon: 'pi pi-trash',
        command: () => {
            if (selectedRowData.value?.id) {
                // Add delete confirmation logic
                console.log('Delete PO:', selectedRowData.value.id);
            }
        },
    }
];

// Safe context menu toggle
function toggleContextMenu(event, rowData) {
    if (rowData) {
        selectedRowData.value = rowData;
        contextMenu.value?.toggle(event);
    }
}

// Helper Functions
const getStatusSeverity = (status) => {
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
};

const formatCurrency = (value) => {
    if (value == null) return '';
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Initialize from URL params
if (props.urlParams) {
    parseUrlParams(props.urlParams);
}
</script>

<template>
    <Head :title="'Purchase Orders'" />

    <AuthenticatedLayout
        :page-title="'Purchase Orders'"
        :breadcrumbs="[
            { label: 'Dashboard', route: route('dashboard') },
            { label: 'Purchase Orders', route: route('purchase-orders.index') },
            { label: 'List' }
        ]"
    >
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
            <ResponsiveCard spacingClasses="p-3">
                <Menu
                    ref="contextMenu"
                    class="shadow"
                    :model="contextMenuItems"
                    popup
                />
                <DataTable
                    lazy
                    paginator
                    stripedRows
                    showGridlines
                    removableSort
                    resizableColumns
                    columnResizeMode="fit"
                    :value="purchaseOrders.data"
                    :totalRecords="purchaseOrders.total"
                    :filters="filters"
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
                    dataKey="id"
                >
                    <!-- Columns definition with safe nested property handling -->
                    <Column
                        field="number"
                        header="PO Number"
                        sortable
                        :showFilterMenu="false"
                    >
                        <template #filter="{ filterModel }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                class="w-full p-2"
                                placeholder="Search by PO#"
                            />
                        </template>
                    </Column>

                    <Column
                        field="supplier.name"
                        header="Supplier"
                        sortable
                        :showFilterMenu="false"
                    >
                        <template #filter="{ filterModel }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                class="w-full p-2"
                                placeholder="Search supplier"
                            />
                        </template>
                        <template #body="{ data }">
                            {{ data.supplier?.name }}
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
                                class="w-full p-2"
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
                                class="w-full p-2"
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
        </Container>
    </AuthenticatedLayout>
</template>
