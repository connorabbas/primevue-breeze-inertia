import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { FilterMatchMode } from '@primevue/core/api';

export function useLazyDataTable(
    defaultFilters = {},
    only = ['request'],
    rows = 20
) {
    const page = usePage();

    const dataTableDefaults = {
        filters: defaultFilters,
        sortField: '',
        sortOrder: 1,
        currentPage: 1,
        rowsPerPage: rows,
    };

    const filters = ref(dataTableDefaults.filters);
    const sortField = ref(dataTableDefaults.sortField);
    const sortOrder = ref(dataTableDefaults.sortOrder);
    const currentPage = ref(dataTableDefaults.currentPage);
    const rowsPerPage = ref(dataTableDefaults.rowsPerPage);

    const firstDatasetIndex = computed(() => {
        return (currentPage.value - 1) * rowsPerPage.value;
    });
    const hasFilteringApplied = computed(() => {
        const filters = page.props?.request?.urlParams?.filters || {};
        const sortField = page.props?.request?.urlParams?.sortField || null;
        const isFiltering = Object.values(filters).some(
            (filter) => filter.value !== null && filter.value !== ''
        );
        const isSorting = sortField !== null && sortField !== '';

        return isFiltering || isSorting;
    });

    const debounceInputFilter = debounce((filterCallback) => {
        filterCallback();
    }, 300);

    function fetchData() {
        return new Promise((resolve, reject) => {
            router.reload({
                only: ['request', ...new Set(only)],
                data: {
                    filters: filters.value,
                    sortField: sortField.value,
                    sortOrder: sortOrder.value,
                    page: currentPage.value,
                    rows: rowsPerPage.value,
                },
                preserveState: true,
                showProgress: true,
                onSuccess: (page) => {
                    resolve(page);
                },
                onError: (errors) => {
                    reject(errors);
                },
            });
        });
    }

    function onFilter(event) {
        currentPage.value = 1;
        filters.value = event.filters;
        // empty arrays cause filtering issues, set to null instead
        Object.keys(filters.value).forEach((key) => {
            const filter = filters.value[key];
            if (Array.isArray(filter.value) && filter.value.length === 0) {
                filters.value[key].value = null;
            }
        });
        fetchData();
    }

    function onSort(event) {
        currentPage.value = 1;
        sortField.value = event.sortField;
        sortOrder.value = event.sortOrder;
        fetchData();
    }

    function onPage(event) {
        currentPage.value = event.page + 1;
        rowsPerPage.value = event.rows;
        fetchData().then(() => {
            scrollToTop();
        });
    }

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }

    function resetTable() {
        window.history.replaceState(null, '', window.location.pathname);
        filters.value = dataTableDefaults.filters;
        sortField.value = dataTableDefaults.sortField;
        sortOrder.value = dataTableDefaults.sortOrder;
        currentPage.value = dataTableDefaults.currentPage;
        rowsPerPage.value = dataTableDefaults.rowsPerPage;
        router.reload({
            only: ['request', ...new Set(only)],
        });
    }

    /**
     * WIP, parse url params into useable filters
     */
    function parseUrlParams(urlParams) {
        filters.value = urlParams?.filters || dataTableDefaults.filters;
        Object.keys(filters.value).forEach((key) => {
            const filter = filters.value[key];
            if (!filter?.value || !filter?.matchMode) {
                return;
            }
            if (filter.matchMode == FilterMatchMode.DATE_IS) {
                filters.value[key].value = new Date(filter.value);
            } else if (
                typeof filter.value === 'string' &&
                !isNaN(filter.value)
            ) {
                filters.value[key].value = Number(filter.value);
            } else if (
                Array.isArray(filter.value) ||
                filter.matchMode == FilterMatchMode.IN
            ) {
                // TODO: find out why there are duplicate array values in multi-select filters
                // "Fixed" with reassigning to unique array
                const unique = [...new Set(filter.value)];
                filter.value = unique;
                filter.value.forEach((value, index) => {
                    if (typeof value === 'string' && !isNaN(value)) {
                        filter.value[index] = Number(value);
                    }
                });
            }
        });
        sortField.value = urlParams?.sortField || dataTableDefaults.sortField;
        sortOrder.value =
            parseInt(urlParams?.sortOrder) || dataTableDefaults.sortOrder;
        currentPage.value =
            parseInt(urlParams?.page) || dataTableDefaults.currentPage;
        rowsPerPage.value =
            parseInt(urlParams?.rows) || dataTableDefaults.rowsPerPage;
    }

    onMounted(() => {
        parseUrlParams(page.props.request.urlParams);
    });

    return {
        filters,
        sortField,
        sortOrder,
        currentPage,
        rowsPerPage,
        firstDatasetIndex,
        hasFilteringApplied,
        debounceInputFilter,
        onFilter,
        onSort,
        onPage,
        resetTable,
        fetchData,
        parseUrlParams,
    };
}
