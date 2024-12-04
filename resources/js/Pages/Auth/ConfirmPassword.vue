<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <!-- Centered Content -->
        <div
            class="flex min-h-screen flex-col items-center justify-center bg-gray-100"
        >
            <div
                class="w-full max-w-md rounded-md bg-white px-6 py-8 shadow-md"
            >
                <!-- Header -->
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Confirm Password
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        This is a secure area of the application. Please confirm
                        your password before continuing.
                    </p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            autofocus
                            placeholder="Enter your password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mt-6">
                        <PrimaryButton
                            class="w-full justify-center"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Confirm
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
