<script setup lang="ts">
import { ref, watch } from 'vue';
import { AgGridVue } from 'ag-grid-vue3';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';
import { GridApi, ICellRendererParams } from 'ag-grid-community';
import { Book } from '@/types/book';
import { Review } from '@/types/review';

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

const columnDefs = ref([
    {
        headerName: 'Cover',
        field: 'cover_image',
        cellRenderer: (params: ICellRendererParams) =>
            `<img src="${params.value}" alt="Book Cover" style="width: 50px; height: 50px; border-radius: 4px;" />`,
        sortable: false,
        filter: false,
        width: 100,
    },
    { headerName: 'Title', field: 'title', sortable: true, filter: true },
    { headerName: 'Author', field: 'author', sortable: true, filter: true },
    {
        headerName: 'Is Available',
        field: 'is_available',
        cellRenderer: (params: ICellRendererParams) => {
            return params.value ? 'Yes' : 'No';
        },
        sortable: true,
        filter: true,
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
    },
    {
        headerName: 'Actions',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerText = 'View Book Details/Check Out';
            button.className =
                'bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600';
            button.addEventListener('click', () => {
                emits('openBookDetails', params.data);
            });
            return button;
        },
        sortable: false,
        filter: false,
    },
]);

if (props.userRole === 'librarian') {
    // Edit column
    columnDefs.value.push({
        headerName: 'Edit',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerHTML = '✏️';
            button.className =
                'bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600';
            button.addEventListener('click', () => {
                emits('openAddEditBook', params.data);
            });
            return button;
        },
        sortable: false,
        filter: false,
    });

    // Delete column
    columnDefs.value.push({
        headerName: 'Delete',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerHTML = '🗑️';
            button.className =
                'bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600';
            button.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this book?')) {
                    emits('deleteBook', params.data.id);
                }
            });
            return button;
        },
        sortable: false,
        filter: false,
    });
}

if (props.userRole === 'customer') {
    columnDefs.value.push({
        headerName: 'Add Review',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerText = 'Add Review';
            button.className =
                'bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600';
            button.addEventListener('click', () => {
                emits('openReview', params.data);
            });
            return button;
        },
        sortable: false,
        filter: false,
    });

    columnDefs.value.push({
        headerName: 'Your Rating',
        field: 'reviews',
        cellRenderer: (params: ICellRendererParams) => {
            const reviews = params.data.reviews || [];
            const userReview = reviews.length > 0 ? reviews[0] : null;
            return userReview ? `Rating: ${userReview.rating}` : 'Not Rated';
        },
        sortable: false,
        filter: false,
        width: 150,
    });

    columnDefs.value.push({
        headerName: 'Edit Rating',
        field: 'reviews',
        cellRenderer: (params: ICellRendererParams) => {
            const reviews = params.data.reviews || [];
            const userReview = reviews.length > 0 ? reviews[0] : null;
            if (userReview) {
                const editButton = document.createElement('button');
                editButton.textContent = 'Edit';
                editButton.className =
                    'bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600';
                editButton.addEventListener('click', () => {
                    emits('openReview', params.data);
                });
                return editButton;
            }
            return document.createElement('span');
        },
        sortable: false,
        filter: false,
        width: 150,
    });

    columnDefs.value.push({
        headerName: 'Delete Rating',
        field: 'reviews',
        cellRenderer: (params: ICellRendererParams) => {
            const reviews = params.data.reviews || [];
            const userReview = reviews.length > 0 ? reviews[0] : null;
            if (userReview) {
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Delete';
                deleteButton.className =
                    'bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600';
                deleteButton.addEventListener('click', () => {
                    if (
                        confirm('Are you sure you want to delete this review?')
                    ) {
                        emits('deleteReview', userReview.id);
                    }
                });
                return deleteButton;
            }
            return '';
        },
        sortable: false,
        filter: false,
        width: 150,
    });
}

watch(
    () => props.searchQuery,
    (newValue) => {
        if (gridApi.value) {
            gridApi.value.setGridOption('quickFilterText', newValue);
        }
    }
);

const gridOptions = {
    onGridReady: (params: any) => {
        gridApi.value = params.api;
    },
};
</script>

<template>
    <div
        class="ag-theme-alpine my-4"
        style="height: 400px; width: 100%"
    >
        <AgGridVue
            :rowData="props.books"
            :columnDefs="columnDefs"
            :gridOptions="gridOptions"
            domLayout="autoHeight"
        />
    </div>
</template>
