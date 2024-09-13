import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

export function useDataTable(defaultFilters = {}, only = ['urlParams']) {
    const dataTableDefaults = {
        filters: defaultFilters,
        sortField: '',
        sortOrder: 1,
        currentPage: 1,
        rowsPerPage: 20,
    };

    const filters = ref(dataTableDefaults.filters);
    const sortField = ref(dataTableDefaults.sortField);
    const sortOrder = ref(dataTableDefaults.sortOrder);
    const currentPage = ref(dataTableDefaults.currentPage);
    const rowsPerPage = ref(dataTableDefaults.rowsPerPage);

    const firstDatasetIndex = computed(() => {
        return (currentPage.value - 1) * rowsPerPage.value;
    });

    function fetchData() {
        return new Promise((resolve, reject) => {
            router.reload({
                only,
                data: {
                    filters: filters.value,
                    sortField: sortField.value,
                    sortOrder: sortOrder.value,
                    page: currentPage.value,
                    rows: rowsPerPage.value,
                },
                preserveState: true,
                onSuccess: (page) => {
                    resolve(page);
                },
                onError: (errors) => {
                    reject(errors);
                },
            });
        });
    }

    function onPage(event) {
        currentPage.value = event.page + 1;
        rowsPerPage.value = event.rows;
        fetchData().then(() => {
            scrollToTop();
        });
    }

    function onSort(event) {
        sortField.value = event.sortField;
        sortOrder.value = event.sortOrder;
        fetchData();
    }

    function onFilter(event) {
        currentPage.value = 1;
        filters.value = event.filters;
        fetchData();
    }

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }

    function reset() {
        filters.value = dataTableDefaults.filters;
        sortField.value = dataTableDefaults.sortField;
        sortOrder.value = dataTableDefaults.sortOrder;
        currentPage.value = dataTableDefaults.currentPage;
        rowsPerPage.value = dataTableDefaults.rowsPerPage;
        fetchData();
    }

    function parseUrlParams(urlParams) {
        filters.value = urlParams?.filters || dataTableDefaults.filters;
        sortField.value = urlParams?.sortField || dataTableDefaults.sortField;
        sortOrder.value =
            parseInt(urlParams?.sortOrder) || dataTableDefaults.sortOrder;
        currentPage.value =
            parseInt(urlParams?.page) || dataTableDefaults.currentPage;
        rowsPerPage.value =
            parseInt(urlParams?.rows) || dataTableDefaults.rowsPerPage;
    }

    return {
        filters,
        sortField,
        sortOrder,
        currentPage,
        rowsPerPage,
        firstDatasetIndex,
        onPage,
        onSort,
        onFilter,
        reset,
        fetchData,
        parseUrlParams,
    };
}
