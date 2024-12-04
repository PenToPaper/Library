<template>
    <button
        @click="onClick"
        class="bg-red-500 text-white px-3 rounded hover:bg-red-600"
    >
        🗑️
    </button>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    if (confirm('Are you sure you want to delete this book?')) {
        props.params.context.emits('deleteBook', props.params.data.id);
    }
}
</script>
