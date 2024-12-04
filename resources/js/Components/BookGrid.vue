<script setup lang="ts">
import ActionButtonCellRenderer from '@/Components/BookGrid/ActionButtonCellRenderer.vue';
import AddReviewButtonCellRenderer from '@/Components/BookGrid/AddReviewButtonCellRenderer.vue';
import CoverCellRenderer from '@/Components/BookGrid/CoverCellRenderer.vue';
import DeleteButtonCellRenderer from '@/Components/BookGrid/DeleteButtonCellRenderer.vue';
import DeleteRatingButtonCellRenderer from '@/Components/BookGrid/DeleteRatingButtonCellRenderer.vue';
import EditButtonCellRenderer from '@/Components/BookGrid/EditButtonCellRenderer.vue';
import EditRatingButtonCellRenderer from '@/Components/BookGrid/EditRatingButtonCellRenderer.vue';
import { Book } from '@/types/book';
import { Review } from '@/types/review';
import { ColDef, GridApi, ICellRendererParams } from 'ag-grid-community';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';
import { AgGridVue } from 'ag-grid-vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    books: Book[];
    userRole: string;
    searchQuery: string;
}>();

const emits = defineEmits<{
    (e: 'openBookDetails', book: Book): void;
    (e: 'openAddEditBook', book?: Book): void;
    (e: 'deleteBook', book: Book): void;
    (e: 'openReview', book: Book): void;
    (e: 'deleteReview', review: Review): void;
}>();

const gridApi = ref<GridApi | null>(null);

const components = {
    CoverCellRenderer,
    ActionButtonCellRenderer,
    EditButtonCellRenderer,
    DeleteButtonCellRenderer,
    AddReviewButtonCellRenderer,
    EditRatingButtonCellRenderer,
    DeleteRatingButtonCellRenderer,
};

const columnDefs = ref<ColDef[]>([
    {
        headerName: 'Cover',
        field: 'cover_image',
        cellRenderer: 'CoverCellRenderer',
        sortable: false,
        filter: false,
        width: 100,
    },
    { headerName: 'Title', field: 'title', sortable: true, filter: true },
    { headerName: 'Author', field: 'author', sortable: true, filter: true },
    {
        headerName: 'Is Available',
        field: 'is_available',
        cellRenderer: (params: ICellRendererParams) =>
            params.value ? 'Yes' : 'No',
        sortable: true,
        filter: true,
        width: 130,
    },
    {
        headerName: 'Reviews',
        cellRenderer: (params: ICellRendererParams) => {
            const average = params.data.average_rating;
            const count = params.data.review_count;
            return average !== null
                ? `${average.toFixed(1)} (${count})`
                : 'No Reviews';
        },
        sortable: true,
        filter: true,
        width: 120,
    },
    {
        headerName: 'Actions',
        cellRenderer: 'ActionButtonCellRenderer',
        sortable: false,
        filter: false,
        width: 250,
    },
]);

// Add conditional columns based on user role
if (props.userRole === 'librarian') {
    columnDefs.value.push(
        {
            headerName: 'Edit',
            cellRenderer: 'EditButtonCellRenderer',
            sortable: false,
            filter: false,
            width: 150,
        },
        {
            headerName: 'Delete',
            cellRenderer: 'DeleteButtonCellRenderer',
            sortable: false,
            filter: false,
            width: 150,
        },
    );
}

if (props.userRole === 'customer') {
    columnDefs.value.push(
        {
            headerName: 'Add Review',
            cellRenderer: 'AddReviewButtonCellRenderer',
            sortable: false,
            filter: false,
            width: 150,
        },
        {
            headerName: 'Your Rating',
            field: 'reviews',
            cellRenderer: (params: ICellRendererParams) => {
                const reviews = params.data.reviews || [];
                const userReview = reviews.length > 0 ? reviews[0] : null;
                return userReview
                    ? `Rating: ${userReview.rating}`
                    : 'Not Rated';
            },
            sortable: false,
            filter: false,
            width: 150,
        },
        {
            headerName: 'Edit Rating',
            cellRenderer: 'EditRatingButtonCellRenderer',
            sortable: false,
            filter: false,
            width: 150,
        },
        {
            headerName: 'Delete Rating',
            cellRenderer: 'DeleteRatingButtonCellRenderer',
            sortable: false,
            filter: false,
            width: 150,
        },
    );
}

watch(
    () => props.searchQuery,
    (newValue) => {
        if (gridApi.value) {
            gridApi.value.setGridOption('quickFilterText', newValue);
        }
    },
);

const gridOptions = {
    onGridReady: (params: any) => {
        gridApi.value = params.api;
    },
    rowHeight: 50,
    context: {
        emits,
    },
};
</script>

<template>
    <div class="ag-theme-alpine my-4" style="height: 400px; width: 100%">
        <AgGridVue
            :rowData="props.books"
            :columnDefs="columnDefs"
            :gridOptions="gridOptions"
            :components="components"
            domLayout="autoHeight"
        />
    </div>
</template>
