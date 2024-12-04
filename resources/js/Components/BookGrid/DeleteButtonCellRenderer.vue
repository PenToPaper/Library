<template>
    <button
        @click="onClick"
        class="rounded bg-red-500 px-3 text-white hover:bg-red-600"
    >
        🗑️
    </button>
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    if (confirm('Are you sure you want to delete this book?')) {
        props.params.context.emits('deleteBook', props.params.data);
    }
}
</script>
