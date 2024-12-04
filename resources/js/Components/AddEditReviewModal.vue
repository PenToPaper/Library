<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import { Book } from '@/types/book';
import { Review } from '@/types/review';
import { ref, watch } from 'vue';

const props = defineProps<{
    book: Book | null;
    review: Review | null;
}>();

const emits = defineEmits<{
    (e: 'close'): void;
    (e: 'submitReview', review: Review): void;
}>();

const localReviewData = ref({
    rating: 0,
    message: '',
    id: 0,
    book_id: 0,
    user_id: 0,
    created_at: '',
    updated_at: '',
});

watch(
    () => props.review,
    (newVal) => {
        if (newVal) {
            localReviewData.value = { ...newVal };
        }
    },
    { immediate: true },
);

const onSubmit = () => {
    emits('submitReview', localReviewData.value);
};
</script>

<template>
    <Modal :show="props.book !== null" @close="emits('close')">
        <div v-if="props.book" class="p-8">
            <h3 class="text-center text-xl font-semibold">
                Reviewing "{{ props.book.title }}"
            </h3>
            <form @submit.prevent="onSubmit">
                <!-- Rating -->
                <div class="mb-4">
                    <label class="mb-1 block">Rating (1-5)</label>
                    <input
                        v-model.number="localReviewData.rating"
                        type="number"
                        min="1"
                        max="5"
                        class="w-full rounded-md border px-4 py-2"
                        required
                    />
                </div>

                <!-- Message -->
                <div class="mb-4">
                    <label class="mb-1 block">Review Message</label>
                    <textarea
                        v-model="localReviewData.message"
                        class="w-full rounded-md border px-4 py-2"
                        rows="4"
                        required
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <button
                        type="submit"
                        class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                    >
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>
