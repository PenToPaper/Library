<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AgGridVue } from 'ag-grid-vue3';
import Modal from '@/Components/Modal.vue';
import { Book } from '@/types/book';
import { ICellRendererParams } from 'ag-grid-community';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';

const books = ref<Book[]>([]);

const fetchBooks = async () => {
    try {
        const response = await fetch('/books');
        if (!response.ok) return;
        books.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch books:', error);
    }
};

const selectedBook = ref<Book | null>(null);

const openModal = (book: Book) => {
    selectedBook.value = book;
};

const closeModal = () => {
    selectedBook.value = null;
};

// Column definitions for ag-Grid
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
        headerName: 'Actions',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerText = 'Check Availability';
            button.className =
                'bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600';
            button.addEventListener('click', () => {
                openModal(params.data);
            });
            return button;
        },
        sortable: false,
        filter: false,
    },
]);

watch(selectedBook, (newVal) => {
    console.log('Selected Book:', newVal); // Debugging
});

onMounted(() => {
    fetchBooks();
});
</script>


<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <div class="p-6">
            <h2 class="text-xl font-semibold leading-tight text-gray-100">
                Library
            </h2>

            <!-- Book Table -->
            <div
                class="ag-theme-alpine my-4"
                style="height: 400px; width: 100%"
            >
                <AgGridVue
                    class="ag-theme-alpine"
                    :rowData="books"
                    :columnDefs="columnDefs"
                    domLayout="autoHeight"
                />
            </div>

            <!-- Modal -->
            <Modal :show="selectedBook !== null" @close="closeModal">
                <h3 class="text-lg font-semibold">
                    {{ selectedBook?.title }}
                </h3>
                <img
                    :src="selectedBook?.cover_image"
                    alt="Book Cover"
                    class="mb-4 w-full rounded-md"
                />
                <p><strong>Author:</strong> {{ selectedBook?.author }}</p>
                <p><strong>Description:</strong> {{ selectedBook?.description }}</p>
                <p><strong>Publisher:</strong> {{ selectedBook?.publisher }}</p>
                <p><strong>Publication Date:</strong> {{ selectedBook?.publication_date }}</p>
                <p><strong>Category:</strong> {{ selectedBook?.category }}</p>
                <p><strong>ISBN:</strong> {{ selectedBook?.isbn }}</p>
                <p><strong>Page Count:</strong> {{ selectedBook?.page_count }}</p>
                <button
                    @click="closeModal"
                    class="rounded-md bg-gray-300 px-4 py-2"
                >
                    Close
                </button>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

