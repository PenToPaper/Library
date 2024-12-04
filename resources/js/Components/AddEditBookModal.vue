<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import { Book } from '@/types/book';
import { ref, watch } from 'vue';

const props = defineProps<{
    book: Book | null;
}>();

const emits = defineEmits<{
    (e: 'close'): void;
    (e: 'saveBook', book: Book): void;
}>();

const localBook = ref<Book | null>(null);

watch(
    () => props.book,
    (newVal) => {
        localBook.value = newVal ? { ...newVal } : null;
    },
    { immediate: true },
);

const onSubmit = () => {
    if (localBook.value) {
        emits('saveBook', localBook.value);
    }
};
</script>

<template>
    <Modal :show="localBook !== null" @close="emits('close')">
        <div v-if="localBook" class="p-8">
            <h3 class="text-center text-xl font-semibold">
                {{ localBook.id === 0 ? 'Add New Book' : 'Edit Book' }}
            </h3>
            <form @submit.prevent="onSubmit">
                <!-- Title -->
                <div class="mb-4">
                    <label class="mb-1 block">Title</label>
                    <input
                        v-model="localBook!.title"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                        required
                    />
                </div>

                <!-- Author -->
                <div class="mb-4">
                    <label class="mb-1 block">Author</label>
                    <input
                        v-model="localBook!.author"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                        required
                    />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="mb-1 block">Description</label>
                    <textarea
                        v-model="localBook!.description"
                        class="w-full rounded-md border px-4 py-2"
                        rows="4"
                    ></textarea>
                </div>

                <!-- Cover Image -->
                <div class="mb-4">
                    <label class="mb-1 block">Cover Image (URL)</label>
                    <input
                        v-model="localBook!.cover_image"
                        type="url"
                        class="w-full rounded-md border px-4 py-2"
                    />
                </div>

                <!-- Publisher -->
                <div class="mb-4">
                    <label class="mb-1 block">Publisher</label>
                    <input
                        v-model="localBook!.publisher"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                    />
                </div>

                <!-- Publication Date -->
                <div class="mb-4">
                    <label class="mb-1 block">Publication Date</label>
                    <input
                        v-model="localBook!.publication_date"
                        type="date"
                        class="w-full rounded-md border px-4 py-2"
                    />
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="mb-1 block">Category</label>
                    <input
                        v-model="localBook!.category"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                    />
                </div>

                <!-- ISBN -->
                <div class="mb-4">
                    <label class="mb-1 block">ISBN</label>
                    <input
                        v-model="localBook!.isbn"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                    />
                </div>

                <!-- Page Count -->
                <div class="mb-4">
                    <label class="mb-1 block">Page Count</label>
                    <input
                        v-model="localBook!.page_count"
                        type="number"
                        class="w-full rounded-md border px-4 py-2"
                        min="1"
                    />
                </div>

                <div class="mt-4">
                    <button
                        type="submit"
                        class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                    >
                        Save Book
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>
