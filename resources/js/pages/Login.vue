<template>
    <section
        class="bg-gradient-to-b from-primary-300 via-primary-500 to-primary-700"
    >
        <div
            class="mx-auto flex flex-col items-center justify-center px-6 py-8 md:h-screen lg:py-0"
        >
            <div
                class="w-full rounded-lg bg-white shadow sm:max-w-md md:mt-0 xl:p-0 dark:border dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="space-y-4 p-6 sm:p-8 md:space-y-6">
                    <h1
                        class="mb-1 text-xl leading-tight font-bold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                    >
                        Simple Mapping Tracer
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400">
                        Sign in to your account
                    </p>
                    <form
                        @submit.prevent="submit"
                        class="space-y-4 md:space-y-6"
                    >
                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                            >
                                Your email
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                name="email"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.email }"
                                placeholder="name@company.com"
                                required
                            />
                            <div
                                v-if="form.errors.email"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
                            >
                                Password
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                :class="{
                                    'border-red-500': form.errors.password,
                                }"
                                required
                            />
                            <div
                                v-if="form.errors.password"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.password }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input
                                        id="remember"
                                        v-model="form.remember"
                                        type="checkbox"
                                        name="remember"
                                        class="h-4 w-4 rounded border border-gray-300 bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label
                                        for="remember"
                                        class="text-gray-500 dark:text-gray-300"
                                    >
                                        Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-lg bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        >
                            {{ form.processing ? 'Signing in...' : 'Sign in' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

defineProps<{
    errors?: Record<string, string>;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.reset('password');
        },
        onSuccess: () => {
            // Will automatically redirect to /dashboard via controller
        },
        onError: (errors) => {
            console.log('Login errors:', errors);
        },
    });
};
</script>
