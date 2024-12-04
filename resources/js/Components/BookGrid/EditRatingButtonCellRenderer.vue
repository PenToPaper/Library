<template>
    <button
        v-if="hasReview"
        @click="onClick"
        class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600"
    >
        Edit
    </button>
    <span v-else></span>
</template>

<script setup lang="ts">
import { defineProps, computed } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

const hasReview = computed(() => {
    const reviews = props.params.data.reviews || [];
    return reviews.length > 0;
});

function onClick() {
    props.params.context.emits('openReview', props.params.data);
}
</script>
