<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Welcome to the Library" />
    <div class="flex min-h-screen flex-col bg-gray-100">
        <!-- Header -->
        <header
            class="flex items-center justify-between bg-white p-6 shadow-md"
        >
            <div class="text-2xl font-bold text-gray-800">Library</div>
            <!-- Navigation Links -->
            <nav>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('library')"
                    class="px-4 text-gray-800 hover:text-gray-600"
                >
                    Library
                </Link>
                <template v-else>
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="px-4 text-gray-800 hover:text-gray-600"
                    >
                        Log In
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 text-gray-800 hover:text-gray-600"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex flex-grow items-center justify-center">
            <div class="text-center">
                <h1 class="mb-8 text-5xl font-bold text-gray-800">
                    Welcome to the Library!
                </h1>
                <div class="space-x-4">
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="rounded-md bg-blue-600 px-6 py-3 text-lg font-medium text-white transition hover:bg-blue-700"
                    >
                        Log In
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md bg-green-600 px-6 py-3 text-lg font-medium text-white transition hover:bg-green-700"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </main>
    </div>
</template>
