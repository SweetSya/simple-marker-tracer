<template>
    <div
        class="fixed bottom-4 left-1/2 z-[999] w-full max-w-lg -translate-x-1/2"
    >
        <div
            class="mx-2 h-16 rounded-full border border-gray-200 bg-white shadow dark:border-gray-600 dark:bg-gray-700"
        >
            <div class="mx-auto grid h-full max-w-lg grid-cols-5">
                <Link
                    href="/dashboard"
                    data-tooltip-target="tooltip-dashboard"
                    :class="[
                        'group inline-flex flex-col items-center justify-center rounded-s-full px-5 transition-all duration-200',
                        isActiveRoute('/dashboard')
                            ? 'bg-primary-50 dark:bg-primary-900/20'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800',
                    ]"
                >
                    <LayoutDashboard
                        :class="[
                            'h-5 w-5 transition-colors duration-200',
                            isActiveRoute('/dashboard')
                                ? 'text-primary-600 dark:text-primary-400'
                                : 'text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500',
                        ]"
                    />
                    <span class="sr-only">Dashboard</span>
                </Link>
                <div
                    id="tooltip-dashboard"
                    role="tooltip"
                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-gray-700"
                >
                    Dashboard
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <Link
                    href="/history-marker"
                    data-tooltip-target="tooltip-history"
                    :class="[
                        'group inline-flex flex-col items-center justify-center px-5 transition-all duration-200',
                        isActiveRoute('/history-marker')
                            ? 'bg-primary-50 dark:bg-primary-900/20'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800',
                    ]"
                >
                    <MapPinned
                        :class="[
                            'h-5 w-5 transition-colors duration-200',
                            isActiveRoute('/history-marker')
                                ? 'text-primary-600 dark:text-primary-400'
                                : 'text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500',
                        ]"
                    />
                    <span class="sr-only">Markers</span>
                </Link>
                <div
                    id="tooltip-history"
                    role="tooltip"
                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-gray-700"
                >
                    Markers
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <div class="flex items-center justify-center">
                    <Link
                        href="/create-marker"
                        data-tooltip-target="tooltip-new"
                        :class="[
                            'group inline-flex h-10 w-10 items-center justify-center rounded-full font-medium transition-all duration-200 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:focus:ring-primary-800',
                            isActiveRoute('/create-marker')
                                ? 'scale-110 bg-primary-700 shadow-lg'
                                : 'bg-primary-600 hover:scale-105 hover:bg-primary-700',
                        ]"
                    >
                        <Plus class="h-5 w-5 text-white" />
                        <span class="sr-only">New item</span>
                    </Link>
                </div>
                <div
                    id="tooltip-new"
                    role="tooltip"
                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-gray-700"
                >
                    Create New Marker
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <Link
                    href="/user-settings"
                    data-tooltip-target="tooltip-profile"
                    :class="[
                        'group inline-flex flex-col items-center justify-center px-5 transition-all duration-200',
                        isActiveRoute('/user-settings')
                            ? 'bg-primary-50 dark:bg-primary-900/20'
                            : 'hover:bg-gray-50 dark:hover:bg-gray-800',
                    ]"
                >
                    <User
                        :class="[
                            'h-5 w-5 transition-colors duration-200',
                            isActiveRoute('/user-settings')
                                ? 'text-primary-600 dark:text-primary-400'
                                : 'text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500',
                        ]"
                    />
                    <span class="sr-only">Profile</span>
                </Link>
                <div
                    id="tooltip-profile"
                    role="tooltip"
                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-gray-700"
                >
                    Profile
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <button
                    @click="logout"
                    data-tooltip-target="tooltip-logout"
                    class="group inline-flex flex-col items-center justify-center rounded-e-full px-5 transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <LogOut
                        class="h-5 w-5 text-gray-500 transition-colors duration-200 group-hover:text-red-600 dark:text-gray-400 dark:group-hover:text-red-500"
                    />
                    <span class="sr-only">Logout</span>
                </button>
                <div
                    id="tooltip-logout"
                    role="tooltip"
                    class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-gray-700"
                >
                    Logout
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    LogOut,
    MapPinned,
    Plus,
    User,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { computed } from 'vue';

const page = usePage();

// Get current URL path
const currentPath = computed(() => {
    return page.url;
});
// Logout function
const logout = () => {
    Swal.fire({
        title: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
    }).then((result) => {
        if (result.isConfirmed) {
            router.visit('/logout');
        }
    });
};
// Check if route is active
const isActiveRoute = (routePath: string) => {
    return currentPath.value === routePath;
};
</script>
