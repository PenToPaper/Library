<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AgGridVue } from 'ag-grid-vue3';
import Modal from '@/Components/Modal.vue';
import { Book } from '@/types/book';
import { ICellRendererParams, GridApi } from 'ag-grid-community';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';

const props = defineProps<{
    userRole: string;
}>();


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
const searchQuery = ref('');
const gridApi = ref<GridApi | null>(null);
const newBook = ref<Book | null>(null);

const openNewBookModal = () => {
    newBook.value = {
        id: 0,
        title: '',
        author: '',
        description: '',
        cover_image: '',
        publisher: '',
        publication_date: '',
        category: '',
        isbn: '',
        page_count: 0,
        created_at: '',
        updated_at: '',
    }
};

const closeNewBookModal = () => {
    newBook.value = null;
};


const openModal = (book: Book) => {
    selectedBook.value = book;
};

const closeModal = () => {
    selectedBook.value = null;
};

// Hook into gridApi filter
watch(searchQuery, (newValue) => {
    if (gridApi.value) {
        gridApi.value.setGridOption('quickFilterText', newValue);
    }
});

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

if (props.userRole === 'librarian') {
    columnDefs.value.push({
        headerName: 'Delete',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerHTML = '🗑️';
            button.className =
                'bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600';
            button.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this book?')) {
                    deleteBook(params.data.id);
                }
            });
            return button;
        },
        sortable: false,
        filter: false,
    });
}

const gridOptions = {
    onGridReady: (params: any) => {
        gridApi.value = params.api;
    },
};

const getCSRFToken = () => {
    return (
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || ''
    );
};

const submitNewBook = async () => {
    try {
        const response = await fetch('/books', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
            },
            body: JSON.stringify(newBook.value),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Validation errors:', errorData.errors);
            return;
        }

        await fetchBooks();
        closeNewBookModal();
    } catch (error) {
        console.error('Failed to submit new book:', error);
    }
};

const deleteBook = async (bookId: number) => {
    try {
        const response = await fetch(`/books/${bookId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Failed to delete book:', errorData);
            return;
        }

        console.log('Book deleted successfully');
        await fetchBooks(); // Refresh the book list
    } catch (error) {
        console.error('Error deleting book:', error);
    }
};


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

            <div v-if="props.userRole === 'librarian'">
                <button class="bg-green-500 text-white px-4 py-2 rounded" @click="openNewBookModal">
                    Add Book
                </button>
            </div>

            <!-- Quick Search -->
            <div class="mb-4">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search books..."
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                />
            </div>

            <!-- Book Table -->
            <div
                class="ag-theme-alpine my-4"
                style="height: 400px; width: 100%"
            >
                <AgGridVue
                    class="ag-theme-alpine"
                    :rowData="books"
                    :columnDefs="columnDefs"
                    :gridOptions="gridOptions"
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

            <!-- New Book Modal -->
            <Modal :show="newBook !== null" @close="closeNewBookModal">
                <h3 class="text-lg font-semibold">Add New Book</h3>
                <form @submit.prevent="submitNewBook">
                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block mb-1">Title</label>
                        <input
                            v-model="newBook!.title"
                            type="text"
                            class="w-full px-4 py-2 border rounded-md"
                            required
                        />
                    </div>

                    <!-- Author -->
                    <div class="mb-4">
                        <label class="block mb-1">Author</label>
                        <input
                            v-model="newBook!.author"
                            type="text"
                            class="w-full px-4 py-2 border rounded-md"
                            required
                        />
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block mb-1">Description</label>
                        <textarea
                            v-model="newBook!.description"
                            class="w-full px-4 py-2 border rounded-md"
                            rows="4"
                        ></textarea>
                    </div>

                    <!-- Cover Image -->
                    <div class="mb-4">
                        <label class="block mb-1">Cover Image (URL)</label>
                        <input
                            v-model="newBook!.cover_image"
                            type="url"
                            class="w-full px-4 py-2 border rounded-md"
                        />
                    </div>

                    <!-- Publisher -->
                    <div class="mb-4">
                        <label class="block mb-1">Publisher</label>
                        <input
                            v-model="newBook!.publisher"
                            type="text"
                            class="w-full px-4 py-2 border rounded-md"
                        />
                    </div>

                    <!-- Publication Date -->
                    <div class="mb-4">
                        <label class="block mb-1">Publication Date</label>
                        <input
                            v-model="newBook!.publication_date"
                            type="date"
                            class="w-full px-4 py-2 border rounded-md"
                        />
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="block mb-1">Category</label>
                        <input
                            v-model="newBook!.category"
                            type="text"
                            class="w-full px-4 py-2 border rounded-md"
                        />
                    </div>

                    <!-- ISBN -->
                    <div class="mb-4">
                        <label class="block mb-1">ISBN</label>
                        <input
                            v-model="newBook!.isbn"
                            type="text"
                            class="w-full px-4 py-2 border rounded-md"
                        />
                    </div>

                    <!-- Page Count -->
                    <div class="mb-4">
                        <label class="block mb-1">Page Count</label>
                        <input
                            v-model="newBook!.page_count"
                            type="number"
                            class="w-full px-4 py-2 border rounded-md"
                            min="1"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button
                            type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                        >
                            Save Book
                        </button>
                    </div>
                </form>
            </Modal>

        </div>
    </AuthenticatedLayout>
</template>
