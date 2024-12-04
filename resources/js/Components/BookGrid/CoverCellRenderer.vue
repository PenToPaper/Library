<template>
    <img
        :src="params.value"
        :alt="params.data.title"
        class="mx-auto block h-full w-auto"
    />
</template>

<script setup lang="ts">
import type { ICellRendererParams } from 'ag-grid-community';
import { defineProps } from 'vue';

const props = defineProps<{ params: ICellRendererParams }>();
</script>
