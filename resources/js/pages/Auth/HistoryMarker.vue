<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 p-4">
            <!-- Header Section -->
            <div class="mb-8">
                <div
                    class="rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 shadow-sm"
                >
                    <div class="mb-4 flex items-center">
                        <div
                            class="mr-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100"
                        >
                            <svg
                                class="h-6 w-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Marker History
                            </h1>
                            <p class="mt-1 text-gray-600">
                                Manage your saved markers, toggle expiry status,
                                and share with others
                            </p>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div
                            class="rounded-xl border border-blue-100 bg-white p-4"
                        >
                            <div class="flex items-center">
                                <div
                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100"
                                >
                                    <svg
                                        class="h-4 w-4 text-blue-600"
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
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Total Markers
                                    </p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ locations.length }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="rounded-xl border border-green-100 bg-white p-4"
                        >
                            <div class="flex items-center">
                                <div
                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-green-100"
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
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Active
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-green-600"
                                    >
                                        {{ activeCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="rounded-xl border border-red-100 bg-white p-4"
                        >
                            <div class="flex items-center">
                                <div
                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-red-100"
                                >
                                    <svg
                                        class="h-4 w-4 text-red-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 001.414 1.414l3-3z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Expired
                                    </p>
                                    <p class="text-2xl font-bold text-red-600">
                                        {{ expiredCount }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div
                v-if="error"
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg
                            class="h-5 w-5 text-red-400"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-red-800">{{ error }}</div>
                    </div>
                </div>
            </div>

            <!-- Success Messages -->
            <div
                v-if="successMessage"
                class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg
                            class="h-5 w-5 text-green-400"
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

            <!-- Controls and Filters -->
            <div
                class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
            >
                <div
                    class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50 p-6"
                >
                    <div
                        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                Your Markers
                            </h2>
                            <p class="text-sm text-gray-600">
                                {{ filteredLocations.length }} marker{{
                                    filteredLocations.length !== 1 ? 's' : ''
                                }}
                                found
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <!-- Search -->
                            <div class="relative">
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                >
                                    <svg
                                        class="h-4 w-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search markers..."
                                    class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:w-64"
                                />
                            </div>

                            <!-- Filter -->
                            <div class="relative">
                                <select
                                    v-model="statusFilter"
                                    class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-8 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="all">All Markers</option>
                                    <option value="active">Active Only</option>
                                    <option value="expired">
                                        Expired Only
                                    </option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                >
                                    <svg
                                        class="h-4 w-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Markers Grid/List -->
                <div class="p-6">
                    <!-- Empty State -->
                    <div
                        v-if="filteredLocations.length === 0"
                        class="py-16 text-center"
                    >
                        <div
                            class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100"
                        >
                            <svg
                                class="h-10 w-10 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">
                            No markers found
                        </h3>
                        <p class="mb-6 text-gray-600">
                            {{
                                searchQuery || statusFilter !== 'all'
                                    ? 'Try adjusting your search or filter'
                                    : 'Create your first marker to get started'
                            }}
                        </p>
                        <button
                            @click="$inertia.visit('/create-marker')"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 font-medium text-white transition-colors hover:bg-blue-700"
                        >
                            <svg
                                class="mr-2 h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                            Create Marker
                        </button>
                    </div>

                    <!-- Markers List -->
                    <div v-else class="space-y-4">
                        <div
                            v-for="location in filteredLocations"
                            :key="location.id"
                            class="rounded-xl border border-gray-200 bg-gradient-to-r from-white to-gray-50 p-6 transition-all duration-200 hover:border-gray-300 hover:shadow-md"
                        >
                            <div
                                class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                            >
                                <!-- Marker Info -->
                                <div class="flex-1">
                                    <div
                                        class="mb-3 flex flex-wrap items-start justify-between"
                                    >
                                        <div>
                                            <h3
                                                class="mb-1 text-lg font-semibold text-gray-900"
                                            >
                                                {{ location.name }}
                                            </h3>
                                            <div
                                                class="flex flex-wrap items-center gap-3 text-sm text-gray-500"
                                            >
                                                <span
                                                    class="rounded bg-gray-100 px-2 py-1 font-mono break-words"
                                                    >{{
                                                        location.share_code
                                                    }}</span
                                                >
                                                <span class="flex items-center">
                                                    <svg
                                                        class="mr-1 h-4 w-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m6 6V9a3 3 0 00-3-3H7a3 3 0 00-3 3v1"
                                                        />
                                                    </svg>
                                                    {{
                                                        formatDate(
                                                            location.created_at,
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                :class="[
                                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold',
                                                    location.is_expired
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-green-100 text-green-800',
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        'mr-1.5 h-1.5 w-1.5 rounded-full',
                                                        location.is_expired
                                                            ? 'bg-red-500'
                                                            : 'bg-green-500',
                                                    ]"
                                                ></span>
                                                {{
                                                    location.is_expired
                                                        ? 'Expired'
                                                        : 'Active'
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Details Grid -->
                                    <div
                                        class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3"
                                    >
                                        <div
                                            class="rounded-lg border border-blue-100 bg-blue-50 p-3"
                                        >
                                            <div class="flex items-center">
                                                <div
                                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100"
                                                >
                                                    <svg
                                                        class="h-4 w-4 text-blue-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-blue-600"
                                                    >
                                                        Area
                                                    </p>
                                                    <p
                                                        class="text-sm font-bold text-blue-800"
                                                    >
                                                        {{
                                                            location.area_km2.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                        km²
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="rounded-lg border border-purple-100 bg-purple-50 p-3"
                                        >
                                            <div class="flex items-center">
                                                <div
                                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100"
                                                >
                                                    <svg
                                                        class="h-4 w-4 text-purple-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-purple-600"
                                                    >
                                                        Points
                                                    </p>
                                                    <p
                                                        class="text-sm font-bold text-purple-800"
                                                    >
                                                        {{
                                                            location.points_count
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="rounded-lg border border-orange-100 bg-orange-50 p-3"
                                        >
                                            <div class="flex items-center">
                                                <div
                                                    class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100"
                                                >
                                                    <svg
                                                        class="h-4 w-4 text-orange-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs font-medium text-orange-600"
                                                    >
                                                        Expires
                                                    </p>
                                                    <p
                                                        class="text-sm font-bold text-orange-800"
                                                    >
                                                        {{
                                                            formatDateShort(
                                                                location.share_expires_at,
                                                            )
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col gap-2 lg:w-48">
                                    <button
                                        @click="shareLocation(location)"
                                        :disabled="location.is_expired"
                                        :class="[
                                            'flex w-full items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200',
                                            location.is_expired
                                                ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                                : 'transform bg-blue-100 text-blue-700 hover:scale-105 hover:bg-blue-200',
                                        ]"
                                    >
                                        <Share class="mr-2 h-4 w-4" />
                                        Share Marker
                                    </button>

                                    <button
                                        @click="toggleExpiry(location)"
                                        :disabled="isToggling === location.id"
                                        :class="[
                                            'flex w-full items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200',
                                            location.is_expired
                                                ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                : 'bg-orange-100 text-orange-700 hover:bg-orange-200',
                                            isToggling === location.id
                                                ? 'cursor-not-allowed opacity-50'
                                                : 'transform hover:scale-105',
                                        ]"
                                    >
                                        <Clock class="mr-2 h-4 w-4" />
                                        {{
                                            isToggling === location.id
                                                ? 'Processing...'
                                                : location.is_expired
                                                  ? 'Extend Expiry'
                                                  : 'Mark Expired'
                                        }}
                                    </button>

                                    <button
                                        @click="viewLocation(location)"
                                        class="flex w-full transform items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 hover:scale-105 hover:bg-gray-200"
                                    >
                                        <Eye class="mr-2 h-4 w-4" />
                                        View Location
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Share Modal -->
            <div
                v-if="shareModal.show"
                class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black p-4"
            >
                <div
                    class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
                >
                    <!-- Modal Header -->
                    <div
                        class="border-b border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-6"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100"
                                >
                                    <Share class="h-5 w-5 text-blue-600" />
                                </div>
                                <div>
                                    <h3
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        Share Location
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Share this marker with others
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="closeShareModal"
                                class="rounded-lg p-2 transition-colors hover:bg-blue-100"
                            >
                                <X class="h-5 w-5 text-gray-500" />
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <!-- Location Info -->
                        <div class="mb-6 rounded-lg bg-gray-50 p-4">
                            <h4 class="mb-2 font-semibold text-gray-900">
                                {{ shareModal.location?.name }}
                            </h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p>
                                    <span class="font-medium">Code:</span>
                                    {{ shareModal.location?.share_code }}
                                </p>
                                <p>
                                    <span class="font-medium">Expires:</span>
                                    {{
                                        shareModal.location
                                            ? formatDate(
                                                  shareModal.location
                                                      .share_expires_at,
                                              )
                                            : ''
                                    }}
                                </p>
                                <p>
                                    <span class="font-medium">Area:</span>
                                    {{
                                        shareModal.location?.area_km2.toFixed(2)
                                    }}
                                    km²
                                </p>
                            </div>
                        </div>

                        <!-- Share URL -->
                        <div class="mb-6">
                            <label
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Share URL
                            </label>
                            <div class="flex gap-2">
                                <input
                                    ref="shareUrlInput"
                                    :value="shareModal.url"
                                    readonly
                                    class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-3 font-mono text-sm"
                                />
                                <button
                                    @click="copyShareUrl"
                                    :class="[
                                        'rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                                        shareModal.copied
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                                    ]"
                                >
                                    {{
                                        shareModal.copied ? '✓ Copied!' : 'Copy'
                                    }}
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button
                                @click="closeShareModal"
                                class="flex-1 rounded-lg bg-gray-100 px-4 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-200"
                            >
                                Close
                            </button>
                            <button
                                @click="viewLocation(shareModal.location!)"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-3 font-medium text-white transition-colors hover:bg-blue-700"
                            >
                                View Location
                            </button>
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
import { Clock, Eye, Share, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Location {
    id: string;
    name: string;
    share_code: string;
    share_expires_at: string;
    created_at: string;
    updated_at: string;
    is_expired: boolean;
    area_km2: number;
    points_count: number;
    data: any;
}

const props = defineProps<{
    locations: Location[];
    error?: string;
}>();

// Reactive state
const searchQuery = ref('');
const statusFilter = ref('all');
const isToggling = ref<string | null>(null);
const successMessage = ref('');
const shareModal = ref({
    show: false,
    location: null as Location | null,
    url: '',
    copied: false,
});

const shareUrlInput = ref<HTMLInputElement>();

// Computed
const filteredLocations = computed(() => {
    let filtered = props.locations;

    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (location) =>
                location.name.toLowerCase().includes(query) ||
                location.share_code.toLowerCase().includes(query),
        );
    }

    // Filter by status
    if (statusFilter.value === 'active') {
        filtered = filtered.filter((location) => !location.is_expired);
    } else if (statusFilter.value === 'expired') {
        filtered = filtered.filter((location) => location.is_expired);
    }

    return filtered;
});

const activeCount = computed(
    () => props.locations.filter((l) => !l.is_expired).length,
);
const expiredCount = computed(
    () => props.locations.filter((l) => l.is_expired).length,
);

// Methods
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatDateShort = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
};

const toggleExpiry = async (location: Location) => {
    isToggling.value = location.id;

    try {
        const response = await axios.post(
            `/marker/${location.id}/toggle-expiry`,
        );

        if (response.data.success) {
            // Update the location in place
            location.is_expired = response.data.is_expired;
            location.share_expires_at = response.data.new_expiry;

            successMessage.value = response.data.message;

            // Clear success message after 3 seconds
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        }
    } catch (error) {
        console.error('Error toggling expiry:', error);
        alert('Failed to update marker expiry');
    } finally {
        isToggling.value = null;
    }
};

const shareLocation = (location: Location) => {
    const baseUrl = window.location.origin;
    const shareUrl = `${baseUrl}/locate?code=${location.share_code}`;

    shareModal.value = {
        show: true,
        location: location,
        url: shareUrl,
        copied: false,
    };
};

const copyShareUrl = async () => {
    try {
        await navigator.clipboard.writeText(shareModal.value.url);
        shareModal.value.copied = true;

        // Reset copied state after 2 seconds
        setTimeout(() => {
            shareModal.value.copied = false;
        }, 2000);
    } catch (error) {
        // Fallback for older browsers
        if (shareUrlInput.value) {
            shareUrlInput.value.select();
            document.execCommand('copy');
            shareModal.value.copied = true;

            setTimeout(() => {
                shareModal.value.copied = false;
            }, 2000);
        }
    }
};

const closeShareModal = () => {
    shareModal.value = {
        show: false,
        location: null,
        url: '',
        copied: false,
    };
};

const viewLocation = (location: Location) => {
    // Navigate to the shared location view
    window.open(`/locate?code=${location.share_code}`, '_blank');
};
</script>
