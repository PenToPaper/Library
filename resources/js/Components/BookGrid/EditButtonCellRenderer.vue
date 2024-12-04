<template>
    <button
        @click="onClick"
        class="bg-yellow-500 text-white px-3 rounded hover:bg-yellow-600"
    >
        ✏️
    </button>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openAddEditBook', props.params.data);
}
</script>
