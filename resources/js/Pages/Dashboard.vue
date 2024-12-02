<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const books = ref([]);

const fetchBooks = async () => {
    try {
        const response = await fetch('/books');
        if (!response.ok) return;
        books.value = await response.json();
    } catch (error) {
        console.error('Failed to fetch books:', error);
    }
};

onMounted(() => {
    fetchBooks();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="mb-4 text-lg font-bold">Books List</h3>

                        <ul class="space-y-4">
                            <li
                                v-for="book in books"
                                :key="book.id"
                                class="p-4 border border-gray-300 rounded-lg dark:border-gray-700"
                            >
                                <h4 class="text-lg font-semibold">{{ book.title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Author:</strong> {{ book.author }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Publisher:</strong> {{ book.publisher }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Category:</strong> {{ book.category }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
