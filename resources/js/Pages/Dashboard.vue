<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Book } from '@/types/book';
import { Head } from '@inertiajs/vue3';
import { GridApi, ICellRendererParams } from 'ag-grid-community';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';
import { AgGridVue } from 'ag-grid-vue3';
import { onMounted, ref, watch } from 'vue';

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
const addEditBook = ref<Book | null>(null);

const openAddEditBookModal = (
    book: Book = {
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
    },
) => {
    addEditBook.value = book;
};

const closeAddEditBookModal = () => {
    addEditBook.value = null;
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
    // Edit column
    columnDefs.value.push({
        headerName: 'Edit',
        cellRenderer: (params: ICellRendererParams) => {
            const button = document.createElement('button');
            button.innerHTML = '✏️';
            button.className =
                'bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600';
            button.addEventListener('click', () => {
                openAddEditBookModal(params.data);
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
            body: JSON.stringify(addEditBook.value),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Validation errors:', errorData.errors);
            return;
        }

        await fetchBooks();
        closeAddEditBookModal();
    } catch (error) {
        console.error('Failed to submit new book:', error);
    }
};

const editBook = async () => {
    console.log("EDIT BOOK");
    try {
        const response = await fetch(`/books/${addEditBook.value!.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
            },
            body: JSON.stringify(addEditBook.value),
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Validation errors:', errorData.errors);
            return;
        }

        await fetchBooks();
        closeAddEditBookModal();
    } catch (error) {
        console.error('Failed to edit book:', error);
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

const onAddEditBook = () => {
    if (addEditBook.value!.id === 0) {
        submitNewBook();
    } else {
        editBook();
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
                <button
                    class="rounded bg-green-500 px-4 py-2 text-white"
                    @click="openAddEditBookModal"
                >
                    Add Book
                </button>
            </div>

            <!-- Quick Search -->
            <div class="mb-4">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search books..."
                    class="w-full rounded-md border px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
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
                <p>
                    <strong>Description:</strong>
                    {{ selectedBook?.description }}
                </p>
                <p><strong>Publisher:</strong> {{ selectedBook?.publisher }}</p>
                <p>
                    <strong>Publication Date:</strong>
                    {{ selectedBook?.publication_date }}
                </p>
                <p><strong>Category:</strong> {{ selectedBook?.category }}</p>
                <p><strong>ISBN:</strong> {{ selectedBook?.isbn }}</p>
                <p>
                    <strong>Page Count:</strong> {{ selectedBook?.page_count }}
                </p>
                <button
                    @click="closeModal"
                    class="rounded-md bg-gray-300 px-4 py-2"
                >
                    Close
                </button>
            </Modal>

            <!-- Add/Edit Book Modal -->
            <Modal :show="addEditBook !== null" @close="closeAddEditBookModal">
                <h3 class="text-lg font-semibold">Add New Book</h3>
                <form @submit.prevent="onAddEditBook">
                    <!-- Title -->
                    <div class="mb-4">
                        <label class="mb-1 block">Title</label>
                        <input
                            v-model="addEditBook!.title"
                            type="text"
                            class="w-full rounded-md border px-4 py-2"
                            required
                        />
                    </div>

                    <!-- Author -->
                    <div class="mb-4">
                        <label class="mb-1 block">Author</label>
                        <input
                            v-model="addEditBook!.author"
                            type="text"
                            class="w-full rounded-md border px-4 py-2"
                            required
                        />
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="mb-1 block">Description</label>
                        <textarea
                            v-model="addEditBook!.description"
                            class="w-full rounded-md border px-4 py-2"
                            rows="4"
                        ></textarea>
                    </div>

                    <!-- Cover Image -->
                    <div class="mb-4">
                        <label class="mb-1 block">Cover Image (URL)</label>
                        <input
                            v-model="addEditBook!.cover_image"
                            type="url"
                            class="w-full rounded-md border px-4 py-2"
                        />
                    </div>

                    <!-- Publisher -->
                    <div class="mb-4">
                        <label class="mb-1 block">Publisher</label>
                        <input
                            v-model="addEditBook!.publisher"
                            type="text"
                            class="w-full rounded-md border px-4 py-2"
                        />
                    </div>

                    <!-- Publication Date -->
                    <div class="mb-4">
                        <label class="mb-1 block">Publication Date</label>
                        <input
                            v-model="addEditBook!.publication_date"
                            type="date"
                            class="w-full rounded-md border px-4 py-2"
                        />
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="mb-1 block">Category</label>
                        <input
                            v-model="addEditBook!.category"
                            type="text"
                            class="w-full rounded-md border px-4 py-2"
                        />
                    </div>

                    <!-- ISBN -->
                    <div class="mb-4">
                        <label class="mb-1 block">ISBN</label>
                        <input
                            v-model="addEditBook!.isbn"
                            type="text"
                            class="w-full rounded-md border px-4 py-2"
                        />
                    </div>

                    <!-- Page Count -->
                    <div class="mb-4">
                        <label class="mb-1 block">Page Count</label>
                        <input
                            v-model="addEditBook!.page_count"
                            type="number"
                            class="w-full rounded-md border px-4 py-2"
                            min="1"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button
                            type="submit"
                            class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                        >
                            Save Book
                        </button>
                    </div>
                </form>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
