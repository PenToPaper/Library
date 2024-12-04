<template>
    <button
        @click="onClick"
        class="rounded bg-green-500 px-3 text-white hover:bg-green-600"
    >
        Add Review
    </button>
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openReview', props.params.data);
}
</script>
