<template>
    <button
        @click="onClick"
        class="rounded bg-blue-500 px-3 text-white hover:bg-blue-600"
    >
        Check Out & View Details
    </button>
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openBookDetails', props.params.data);
}
</script>
