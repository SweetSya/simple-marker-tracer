<template>
    <AppLayout>
        <div class="min-h-screen p-4">
            <!-- Header -->
            <div
                class="mb-4 rounded-xl border border-primary-200 bg-primary-50 p-4 shadow-sm"
            >
                <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                <p class="mt-1 text-gray-600">
                    Manage your account and customize your experience
                </p>
            </div>

            <!-- Success Messages -->
            <div
                v-if="successMessage"
                class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg
                            class="h-5 w-5 text-green-500"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-green-800">
                            {{ successMessage }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Cards Grid -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Profile Card -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
                >
                    <div
                        class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100"
                                >
                                    <User class="h-5 w-5 text-blue-600" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Profile Information
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Update your personal details
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="updateProfile" class="space-y-5">
                            <div>
                                <label
                                    for="name"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Full Name
                                </label>
                                <div class="relative">
                                    <input
                                        id="name"
                                        v-model="profileForm.name"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-colors focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                        :class="{
                                            'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200':
                                                profileForm.errors.name,
                                        }"
                                        placeholder="Enter your full name"
                                    />
                                </div>
                                <div
                                    v-if="profileForm.errors.name"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ profileForm.errors.name }}
                                </div>
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Email Address
                                </label>
                                <div class="relative">
                                    <input
                                        id="email"
                                        v-model="profileForm.email"
                                        type="email"
                                        required
                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-colors focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                        :class="{
                                            'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200':
                                                profileForm.errors.email,
                                        }"
                                        placeholder="Enter your email address"
                                    />
                                </div>
                                <div
                                    v-if="profileForm.errors.email"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ profileForm.errors.email }}
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    :disabled="profileForm.processing"
                                    class="w-full transform rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 font-medium text-white shadow-sm transition-all duration-200 hover:scale-[1.02] hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <span
                                        v-if="profileForm.processing"
                                        class="flex items-center justify-center"
                                    >
                                        <svg
                                            class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        Updating...
                                    </span>
                                    <span v-else>Update Profile</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Card -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
                >
                    <div
                        class="border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50 px-6 py-5"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100"
                                >
                                    <Lock class="h-5 w-5 text-red-600" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Security
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Change your password
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <form
                            @submit.prevent="updatePassword"
                            class="space-y-5"
                        >
                            <div>
                                <label
                                    for="current_password"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Current Password
                                </label>
                                <input
                                    id="current_password"
                                    v-model="passwordForm.current_password"
                                    type="password"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-colors focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200"
                                    :class="{
                                        'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200':
                                            passwordForm.errors
                                                .current_password,
                                    }"
                                    placeholder="Enter current password"
                                />
                                <div
                                    v-if="passwordForm.errors.current_password"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ passwordForm.errors.current_password }}
                                </div>
                            </div>

                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    New Password
                                </label>
                                <input
                                    id="password"
                                    v-model="passwordForm.password"
                                    type="password"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-colors focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200"
                                    :class="{
                                        'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200':
                                            passwordForm.errors.password,
                                    }"
                                    placeholder="Enter new password"
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Must be at least 8 characters long
                                </p>
                                <div
                                    v-if="passwordForm.errors.password"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ passwordForm.errors.password }}
                                </div>
                            </div>

                            <div>
                                <label
                                    for="password_confirmation"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Confirm Password
                                </label>
                                <input
                                    id="password_confirmation"
                                    v-model="passwordForm.password_confirmation"
                                    type="password"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-colors focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200"
                                    :class="{
                                        'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200':
                                            passwordForm.errors
                                                .password_confirmation,
                                    }"
                                    placeholder="Confirm new password"
                                />
                                <div
                                    v-if="
                                        passwordForm.errors
                                            .password_confirmation
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{
                                        passwordForm.errors
                                            .password_confirmation
                                    }}
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    :disabled="passwordForm.processing"
                                    class="w-full transform rounded-lg bg-gradient-to-r from-red-600 to-pink-600 px-6 py-3 font-medium text-white shadow-sm transition-all duration-200 hover:scale-[1.02] hover:from-red-700 hover:to-pink-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <span
                                        v-if="passwordForm.processing"
                                        class="flex items-center justify-center"
                                    >
                                        <svg
                                            class="mr-3 -ml-1 h-5 w-5 animate-spin text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        Updating...
                                    </span>
                                    <span v-else>Update Password</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm lg:col-span-2"
                >
                    <div
                        class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100"
                                >
                                    <Settings class="h-5 w-5 text-gray-600" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Account Information
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Your account details and status
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div
                            class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4"
                        >
                            <div
                                class="rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-4"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <dt
                                            class="mb-1 text-sm font-medium text-blue-600"
                                        >
                                            User ID
                                        </dt>
                                        <dd
                                            class="font-mono text-xs break-all text-blue-800"
                                        >
                                            {{ user.id }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-200"
                                    >
                                        <User class="h-4 w-4 text-blue-600" />
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-4"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <dt
                                            class="mb-1 text-sm font-medium text-green-600"
                                        >
                                            Status
                                        </dt>
                                        <dd>
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-200 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                            >
                                                <span
                                                    class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"
                                                ></span>
                                                Active
                                            </span>
                                        </dd>
                                    </div>
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-200"
                                    >
                                        <svg
                                            class="h-4 w-4 text-green-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-4"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <dt
                                            class="mb-1 text-sm font-medium text-purple-600"
                                        >
                                            Member Since
                                        </dt>
                                        <dd class="text-xs text-purple-800">
                                            {{
                                                formatDateShort(user.created_at)
                                            }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-200"
                                    >
                                        <svg
                                            class="h-4 w-4 text-purple-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-4"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <dt
                                            class="mb-1 text-sm font-medium text-orange-600"
                                        >
                                            Last Updated
                                        </dt>
                                        <dd class="text-xs text-orange-800">
                                            {{
                                                formatDateShort(user.updated_at)
                                            }}
                                        </dd>
                                    </div>
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-200"
                                    >
                                        <svg
                                            class="h-4 w-4 text-orange-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';
import { Lock, Settings, User } from 'lucide-vue-next';
import { reactive, ref } from 'vue';

interface User {
    id: string;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    user: User;
}>();

// Reactive state
const successMessage = ref('');

// Profile form
const profileForm = reactive({
    name: props.user.name,
    email: props.user.email,
    processing: false,
    errors: {} as Record<string, string>,
});

// Password form
const passwordForm = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
    processing: false,
    errors: {} as Record<string, string>,
});

// Methods
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatDateShort = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

const showSuccess = (message: string) => {
    successMessage.value = message;
    setTimeout(() => {
        successMessage.value = '';
    }, 5000);
};

const updateProfile = async () => {
    profileForm.processing = true;
    profileForm.errors = {};

    try {
        const response = await axios.post('/user-settings/profile', {
            name: profileForm.name,
            email: profileForm.email,
        });

        if (response.data.success) {
            showSuccess(response.data.message);
            Object.assign(props.user, response.data.user);
        }
    } catch (error: any) {
        if (error.response?.status === 422) {
            profileForm.errors = error.response.data.errors || {};
        } else {
            showSuccess('Failed to update profile. Please try again.');
        }
    } finally {
        profileForm.processing = false;
    }
};

const updatePassword = async () => {
    passwordForm.processing = true;
    passwordForm.errors = {};

    try {
        const response = await axios.post('/user-settings/password', {
            current_password: passwordForm.current_password,
            password: passwordForm.password,
            password_confirmation: passwordForm.password_confirmation,
        });

        if (response.data.success) {
            showSuccess(response.data.message);
            passwordForm.current_password = '';
            passwordForm.password = '';
            passwordForm.password_confirmation = '';
        }
    } catch (error: any) {
        if (error.response?.status === 422) {
            passwordForm.errors = error.response.data.errors || {};
        } else {
            showSuccess('Failed to update password. Please try again.');
        }
    } finally {
        passwordForm.processing = false;
    }
};
</script>
