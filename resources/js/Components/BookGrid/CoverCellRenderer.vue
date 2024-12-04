<template>
    <img :src="params.value" :alt="params.data.title" class="h-full w-auto block mx-auto" />
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ICellRendererParams } from 'ag-grid-community';

const props = defineProps<{ params: ICellRendererParams }>();
</script>
