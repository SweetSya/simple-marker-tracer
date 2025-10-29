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
                    class="group inline-flex flex-col items-center justify-center rounded-s-full px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <LayoutDashboard
                        class="h-5 w-5 text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500"
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
                    class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <MapPinned
                        class="h-5 w-5 text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500"
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
                        class="group inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 font-medium hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 focus:outline-none dark:focus:ring-primary-800"
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
                    class="group inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <User
                        class="h-5 w-5 text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500"
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
                <Link
                    href="/logout"
                    data-tooltip-target="tooltip-logout"
                    class="group inline-flex flex-col items-center justify-center rounded-e-full px-5 hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                    <LogOut
                        class="h-5 w-5 text-gray-500 group-hover:text-primary-600 dark:text-gray-400 dark:group-hover:text-primary-500"
                    />
                    <span class="sr-only">Logout</span>
                </Link>
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
import { Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    LogOut,
    MapPinned,
    Plus,
    User,
} from 'lucide-vue-next';
</script>
