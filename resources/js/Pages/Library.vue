<script setup lang="ts">
import AddEditBookModal from '@/Components/AddEditBookModal.vue';
import AddEditReviewModal from '@/Components/AddEditReviewModal.vue';
import BookDetailsModal from '@/Components/BookDetailsModal.vue';
import BookGrid from '@/Components/BookGrid.vue';
import TextInput from '@/Components/TextInput.vue';
import {
    checkOutBook,
    deleteBook,
    getAllBooks,
    markBookReturned,
    submitOrUpdateBook,
} from '@/helpers/bookHelper';
import { deleteReview, submitOrUpdateReview } from '@/helpers/reviewsHelper';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Book } from '@/types/book';
import { Review } from '@/types/review';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    userRole: string;
}>();

const books = ref<Book[]>([]);
const selectedBook = ref<Book | null>(null);
const searchQuery = ref('');

const addEditBook = ref<Book | null>(null);
const reviewBook = ref<Book | null>(null);
const initialReview = {
    rating: 0,
    message: '',
    id: 0,
    book_id: 0,
    user_id: 0,
    created_at: '',
    updated_at: '',
};
const reviewData = ref<Review | null>(initialReview);

const openReviewModal = (book: Book) => {
    reviewBook.value = book;
    if (book.reviews.length) {
        reviewData.value = book.reviews[0];
    } else {
        reviewData.value = null;
    }
};

const closeReviewModal = () => {
    reviewBook.value = null;
};

const performActionAndRefresh = async (
    action: () => Promise<boolean>,
    onSuccess?: () => void,
) => {
    if (await action()) {
        await updateBooks();
        onSuccess?.();
    }
};

const updateBooks = async () => {
    books.value = await getAllBooks();
};

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
        is_available: true,
        average_rating: null,
        review_count: 0,
        reviews: [],
    },
) => {
    addEditBook.value = book;
};

const closeAddEditBookModal = () => {
    addEditBook.value = null;
};

const openBookDetailsModal = (book: Book) => {
    selectedBook.value = book;
};

const closeBookDetailsModal = () => {
    selectedBook.value = null;
};

// Actions
const onSubmitOrUpdateReview = async (review: Review) => {
    await performActionAndRefresh(
        () => submitOrUpdateReview(review),
        closeReviewModal,
    );
};

const onDeleteReview = async (review: Review) => {
    await performActionAndRefresh(() => deleteReview(review), closeReviewModal);
};

const onSaveBook = async (book: Book) => {
    await performActionAndRefresh(
        () => submitOrUpdateBook(book),
        closeAddEditBookModal,
    );
};

const onDeleteBook = async (book: Book) => {
    await performActionAndRefresh(() => deleteBook(book));
};

const onMarkBookReturned = async (book: Book) => {
    await performActionAndRefresh(
        () => markBookReturned(book),
        closeBookDetailsModal,
    );
};

const onCheckOutBook = async (book: Book) => {
    await performActionAndRefresh(
        () => checkOutBook(book),
        closeBookDetailsModal,
    );
};

// Initial setup
onMounted(updateBooks);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Library" />

        <div class="p-6">
            <h2 class="mb-5 text-xl font-semibold leading-tight text-gray-900">
                Welcome to the Library
            </h2>

            <div v-if="props.userRole === 'librarian'">
                <button
                    class="mb-3 rounded bg-green-500 px-4 py-2 text-white"
                    @click="openAddEditBookModal()"
                >
                    Add Book
                </button>
            </div>

            <!-- Quick Search -->
            <div class="mb-4">
                <TextInput
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search books..."
                    class="w-full"
                />
            </div>

            <!-- Book Grid -->
            <BookGrid
                :books="books"
                :userRole="props.userRole"
                :searchQuery="searchQuery"
                @openBookDetails="openBookDetailsModal"
                @openAddEditBook="openAddEditBookModal"
                @deleteBook="onDeleteBook"
                @openReview="openReviewModal"
                @deleteReview="onDeleteReview"
            />

            <!-- Book Details Modal -->
            <BookDetailsModal
                :book="selectedBook"
                :userRole="props.userRole"
                @close="closeBookDetailsModal"
                @markBookReturned="onMarkBookReturned"
                @checkOutBook="onCheckOutBook"
            />

            <!-- Add/Edit Book Modal -->
            <AddEditBookModal
                :book="addEditBook"
                @close="closeAddEditBookModal"
                @saveBook="onSaveBook"
            />

            <!-- Add/Edit Review Modal -->
            <AddEditReviewModal
                :book="reviewBook"
                :review="reviewData"
                @close="closeReviewModal"
                @submitReview="onSubmitOrUpdateReview"
            />
        </div>
    </AuthenticatedLayout>
</template>
