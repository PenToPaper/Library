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
        <div v-if="localBook">
            <h3 class="text-lg font-semibold">
                {{ localBook.id === 0 ? 'Add New Book' : 'Edit Book' }}
            </h3>
            <form @submit.prevent="onSubmit">
                <!-- Title -->
                <div class="mb-4">
                    <label class="mb-1 block">Title</label>
                    <input
                        v-model="localBook.title"
                        type="text"
                        class="w-full rounded-md border px-4 py-2"
                        required
                    />
                </div>
                <!-- Repeat for other fields as in the original code -->
                <!-- ... -->
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
