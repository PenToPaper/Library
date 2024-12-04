<template>
    <button
        @click="onClick"
        class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600"
    >
        Check Out & View Details
    </button>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openBookDetails', props.params.data);
}
</script>
