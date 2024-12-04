<template>
    <button
        @click="onClick"
        class="bg-green-500 text-white px-3 rounded hover:bg-green-600"
    >
        Add Review
    </button>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openReview', props.params.data);
}
</script>
