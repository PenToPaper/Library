<template>
    <button
        v-if="hasReview"
        @click="onClick"
        class="rounded bg-blue-500 px-3 text-white hover:bg-blue-600"
    >
        Edit
    </button>
    <span v-else></span>
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { computed, defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();

const hasReview = computed(() => {
    const reviews = props.params.data.reviews || [];
    return reviews.length > 0;
});

function onClick() {
    props.params.context.emits('openReview', props.params.data);
}
</script>
