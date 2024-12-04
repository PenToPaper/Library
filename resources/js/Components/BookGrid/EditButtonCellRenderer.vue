<template>
    <button
        @click="onClick"
        class="rounded bg-yellow-500 px-3 text-white hover:bg-yellow-600"
    >
        ✏️
    </button>
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();

function onClick() {
    props.params.context.emits('openAddEditBook', props.params.data);
}
</script>
