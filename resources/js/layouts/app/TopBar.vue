<template>
    <header class="antialiased">
        <nav
            class="border-gray-200 bg-white px-4 py-2.5 shadow-sm lg:px-6 dark:bg-gray-800"
        >
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center justify-start">
                    <Link href="/dashboard" class="flex gap-3">
                        <img src="/favicon.ico" alt="" />
                        <span class="mr-3 flex items-center">SMT</span>
                    </Link>
                </div>
                <div class="flex items-center lg:order-2">
                    <!-- Notifications -->
                    <button
                        type="button"
                        data-dropdown-toggle="notification-dropdown"
                        class="mr-1 rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:ring-4 focus:ring-gray-300 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-600"
                    >
                        <span class="sr-only">View notifications</span>
                        <!-- Bell icon -->
                        <svg
                            class="h-5 w-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 14 20"
                        >
                            <path
                                d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"
                            />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div
                        class="z-50 my-4 hidden max-w-sm list-none divide-y divide-gray-100 overflow-hidden rounded bg-white text-base shadow-lg dark:divide-gray-600 dark:bg-gray-700"
                        id="notification-dropdown"
                    >
                        <div
                            class="block bg-gray-50 px-4 py-2 text-center text-base font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-400"
                        >
                            Notifications
                        </div>
                        <div>
                            <a
                                href="#"
                                class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600"
                            >
                                No new notifications
                            </a>
                        </div>
                        <a
                            href="#"
                            class="block bg-gray-50 py-2 text-center text-base font-medium text-gray-900 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:underline"
                        >
                            <div class="inline-flex items-center">
                                <svg
                                    aria-hidden="true"
                                    class="mr-2 h-5 w-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M10 12a2 2 0 100-4 2 2 0 000 4z"
                                    ></path>
                                    <path
                                        fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                View all
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
</script>
