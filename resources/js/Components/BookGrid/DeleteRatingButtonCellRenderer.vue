<template>
    <button
        v-if="hasReview"
        @click="onClick"
        class="bg-red-500 text-white px-3 rounded hover:bg-red-600"
    >
        Delete
    </button>
    <span v-else></span>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

const userReview = computed(() => {
    const reviews = props.params.data.reviews || [];
    return reviews.length > 0 ? reviews[0] : null;
});

const hasReview = computed(() => !!userReview.value);

function onClick() {
    if (confirm('Are you sure you want to delete this review?')) {
        props.params.context.emits('deleteReview', userReview.value.id);
    }
}
</script>
