<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import { Book } from '@/types/book';

const props = defineProps<{
    book: Book | null;
    userRole: string;
}>();

const emits = defineEmits<{
    (e: 'close'): void;
    (e: 'markBookReturned', book: Book): void;
    (e: 'checkOutBook', book: Book): void;
}>();
</script>

<template>
    <Modal :show="book !== null" @close="emits('close')">
        <div v-if="book" class="p-8">
            <h3 class="mb-3 text-center text-xl font-semibold">
                {{ book.title }}
            </h3>
            <img
                :src="book.cover_image"
                alt="Book Cover"
                class="mx-auto mb-4 block w-full max-w-64 rounded-md"
            />
            <p><strong>Author:</strong> {{ book.author }}</p>
            <p><strong>Description:</strong> {{ book.description }}</p>
            <p><strong>Publisher:</strong> {{ book.publisher }}</p>
            <p>
                <strong>Publication Date:</strong> {{ book.publication_date }}
            </p>
            <p><strong>Category:</strong> {{ book.category }}</p>
            <p><strong>ISBN:</strong> {{ book.isbn }}</p>
            <p><strong>Page Count:</strong> {{ book.page_count }}</p>
            <p>
                <strong>Is Available:</strong>
                {{ book.is_available ? 'Yes' : 'No' }}
            </p>

            <div class="flex flex-wrap justify-between">
                <!-- Mark Returned Button -->
                <button
                    v-if="userRole === 'librarian' && !book.is_available"
                    @click="emits('markBookReturned', book)"
                    class="mt-4 rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600"
                >
                    Mark Returned
                </button>

                <button
                    @click="emits('close')"
                    class="mt-4 rounded bg-gray-300 px-4 py-2"
                >
                    Close
                </button>

                <!-- Check Out Button -->
                <button
                    v-if="userRole === 'customer' && book.is_available"
                    @click="emits('checkOutBook', book)"
                    class="mt-4 rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                >
                    Check Out Book
                </button>
            </div>
        </div>
    </Modal>
</template>
