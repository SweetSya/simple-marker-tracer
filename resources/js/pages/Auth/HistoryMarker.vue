<template>
    <AppLayout>
        <div class="min-h-screen p-4">
            <!-- Header Section -->
            <div class="mb-6">
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Shape Collection History
                            </h1>
                            <p class="mt-1 text-gray-600">
                                View and manage your saved locations and shape
                                collections
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div
                v-if="error"
                class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
            >
                <div class="rounded-md border border-red-200 bg-red-50 p-4">
                    <div class="flex">
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
                            <h3 class="text-sm font-medium text-red-800">
                                Error
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                {{ error }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="locations.length === 0" class="py-12 text-center">
                <svg
                    class="mx-auto h-24 w-24 text-gray-300"
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
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">
                    No collections yet
                </h3>
                <p class="mt-2 text-gray-600">
                    Start by creating your first shape collection
                </p>
                <Link
                    href="/create-marker"
                    class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
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
                    Create Collection
                </Link>
            </div>

            <!-- Collections Grid -->
            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="location in locations"
                    :key="location.id"
                    class="group relative overflow-visible rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-200 hover:shadow-lg"
                >
                    <!-- Collection Header -->
                    <div
                        :class="[
                            'border-b p-4',
                            getCollectionHeaderStyle(location),
                        ]"
                    >
                        <div class="flex items-start justify-between">
                            <div class="min-w-0 flex-1">
                                <h3
                                    class="truncate text-lg font-semibold text-gray-900"
                                >
                                    {{ location.name }}
                                </h3>
                                <div
                                    class="mt-1 flex items-center space-x-2 text-sm text-gray-600"
                                >
                                    <span>{{
                                        formatDate(location.created_at)
                                    }}</span>
                                    <span
                                        v-if="isExpired(location)"
                                        class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800"
                                    >
                                        Expired
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800"
                                    >
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <!-- Format Badge -->
                                <span
                                    :class="[
                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                        location.format === 'multi-shape'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-gray-100 text-gray-800',
                                    ]"
                                >
                                    {{
                                        location.format === 'multi-shape'
                                            ? 'Multi-Shape'
                                            : 'Legacy'
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Content -->
                    <div class="p-4">
                        <!-- Multi-Shape Format -->
                        <div v-if="location.format === 'multi-shape'">
                            <div class="mb-3 grid grid-cols-2 gap-3 text-sm">
                                <div class="text-center">
                                    <div
                                        class="text-2xl font-bold text-blue-600"
                                    >
                                        {{ location.shapes_count }}
                                    </div>
                                    <div class="text-gray-600">
                                        Shape{{
                                            location.shapes_count !== 1
                                                ? 's'
                                                : ''
                                        }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="text-2xl font-bold text-green-600"
                                    >
                                        {{ location.total_points }}
                                    </div>
                                    <div class="text-gray-600">
                                        Total Points
                                    </div>
                                </div>
                            </div>

                            <!-- Shape Types Summary -->
                            <div class="mb-3 flex flex-wrap gap-1">
                                <span
                                    v-for="shapeType in location.shape_types"
                                    :key="shapeType"
                                    :class="[
                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                                        shapeType === 'polygon'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-green-100 text-green-700',
                                    ]"
                                >
                                    <svg
                                        class="mr-1 h-3 w-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            v-if="shapeType === 'polygon'"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.382v10.764a1 1 0 01-.553.894L15 18l-6-3z"
                                        />
                                        <path
                                            v-else
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>
                                    {{
                                        shapeType === 'polygon'
                                            ? 'Polygons'
                                            : 'Lines'
                                    }}
                                </span>
                            </div>

                            <!-- Measurements -->
                            <div class="space-y-1 text-sm">
                                <div
                                    v-if="location.total_area_km2 > 0"
                                    class="flex justify-between"
                                >
                                    <span class="text-gray-600"
                                        >Total Area:</span
                                    >
                                    <span class="font-medium"
                                        >{{
                                            location.total_area_km2.toFixed(2)
                                        }}
                                        km²</span
                                    >
                                </div>
                                <div
                                    v-if="location.total_length_km > 0"
                                    class="flex justify-between"
                                >
                                    <span class="text-gray-600"
                                        >Total Length:</span
                                    >
                                    <span class="font-medium"
                                        >{{
                                            location.total_length_km.toFixed(2)
                                        }}
                                        km</span
                                    >
                                </div>
                            </div>

                            <!-- Shape Details -->
                            <div
                                v-if="location.data.shapes"
                                class="mt-3 max-h-32 overflow-y-auto"
                            >
                                <div
                                    class="mb-1 text-xs font-medium text-gray-700"
                                >
                                    Shapes:
                                </div>
                                <div class="space-y-1">
                                    <div
                                        v-for="(
                                            shape, index
                                        ) in location.data.shapes.slice(0, 3)"
                                        :key="index"
                                        class="flex items-center justify-between rounded-md bg-gray-50 p-2"
                                    >
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <div
                                                :class="[
                                                    'h-2 w-2 rounded-full',
                                                    shape.type === 'polygon'
                                                        ? 'bg-blue-500'
                                                        : 'bg-green-500',
                                                ]"
                                            ></div>
                                            <span
                                                class="truncate text-xs font-medium"
                                                >{{ shape.name }}</span
                                            >
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            {{
                                                shape.type === 'polygon'
                                                    ? `${shape.area_km2?.toFixed(1)} km²`
                                                    : `${shape.length_km?.toFixed(1)} km`
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div
                                    v-if="location.data.shapes.length > 3"
                                    class="mt-1 text-center text-xs text-gray-500"
                                >
                                    +{{ location.data.shapes.length - 3 }} more
                                    shapes
                                </div>
                            </div>
                        </div>

                        <!-- Legacy Single Polygon Format -->
                        <div v-else>
                            <div class="mb-3 grid grid-cols-2 gap-3 text-sm">
                                <div class="text-center">
                                    <div
                                        class="text-2xl font-bold text-blue-600"
                                    >
                                        1
                                    </div>
                                    <div class="text-gray-600">Polygon</div>
                                </div>
                                <div class="text-center">
                                    <div
                                        class="text-2xl font-bold text-green-600"
                                    >
                                        {{ location.points_count }}
                                    </div>
                                    <div class="text-gray-600">Points</div>
                                </div>
                            </div>

                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Area:</span>
                                    <span class="font-medium"
                                        >{{
                                            location.area_km2?.toFixed(2) ||
                                            '0.00'
                                        }}
                                        km²</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t bg-gray-50 p-4">
                        <div class="flex space-x-2">
                            <button
                                @click="viewLocation(location)"
                                class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                            >
                                <svg
                                    class="mr-1 inline h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                                View
                            </button>

                            <button
                                @click="showShareModal(location)"
                                :disabled="isExpired(location)"
                                :class="[
                                    'flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                    isExpired(location)
                                        ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                        : 'bg-green-600 text-white hover:bg-green-700',
                                ]"
                            >
                                <svg
                                    class="mr-1 inline h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                    />
                                </svg>
                                {{ isExpired(location) ? 'Expired' : 'Share' }}
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="relative">
                                <button
                                    @click="
                                        openDropdown === null
                                            ? toggleDropdown(location.id)
                                            : openDropdown === location.id
                                              ? toggleDropdown(null)
                                              : toggleDropdown(location.id)
                                    "
                                    class="flex cursor-pointer items-center rounded-lg bg-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-300"
                                >
                                    <EllipsisVertical
                                        v-show="openDropdown != location.id"
                                        class="h-5 w-5"
                                    />
                                    <X
                                        v-show="openDropdown === location.id"
                                        class="h-5 w-5"
                                    />
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    v-if="openDropdown === location.id"
                                    class="dropdown-menu absolute top-full right-0 z-[500] mt-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg"
                                    @click.stop
                                >
                                    <div class="p-1">
                                        <button
                                            @click="editLocation(location)"
                                            class="flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
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
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                />
                                            </svg>
                                            Edit Name
                                        </button>

                                        <button
                                            @click="showExtendModal(location)"
                                            v-if="isExpired(location)"
                                            class="flex w-full items-center rounded-md px-3 py-2 text-sm text-green-700 hover:bg-gray-100"
                                            :disabled="
                                                processingLocation ===
                                                location.id
                                            "
                                        >
                                            <svg
                                                v-if="
                                                    processingLocation !==
                                                    location.id
                                                "
                                                class="mr-2 h-4 w-4"
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
                                            <svg
                                                v-else
                                                class="mr-2 h-4 w-4 animate-spin"
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
                                                />
                                                <path
                                                    class="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                />
                                            </svg>
                                            Extend Expiry
                                        </button>

                                        <div
                                            class="my-1 border-t border-gray-200"
                                        ></div>

                                        <button
                                            @click="deleteLocation(location)"
                                            class="flex w-full items-center rounded-md px-3 py-2 text-sm text-red-700 hover:bg-red-50"
                                            :disabled="
                                                processingLocation ===
                                                location.id
                                            "
                                        >
                                            <svg
                                                v-if="
                                                    processingLocation !==
                                                    location.id
                                                "
                                                class="mr-2 h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                            <svg
                                                v-else
                                                class="mr-2 h-4 w-4 animate-spin"
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
                                                />
                                                <path
                                                    class="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div
                        v-if="!isExpired(location)"
                        class="border-t bg-blue-50 px-4 py-2"
                    >
                        <div class="text-xs text-blue-700">
                            Expires: {{ formatDate(location.share_expires_at) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Name Modal -->
        <div
            v-if="editingLocation"
            class="bg-opacity-50 fixed inset-0 z-[1005] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click="cancelEdit"
        >
            <div
                class="mx-4 w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
                @click.stop
            >
                <h3 class="mb-4 text-xl font-bold text-gray-900">
                    Edit Collection Name
                </h3>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Collection Name
                    </label>
                    <input
                        v-model="editName"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200"
                        @keydown.enter="saveEdit"
                        @keydown.escape="cancelEdit"
                    />
                </div>

                <div class="flex gap-3">
                    <button
                        @click="saveEdit"
                        :disabled="!editName.trim() || isProcessing"
                        :class="[
                            'flex-1 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                            !editName.trim() || isProcessing
                                ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                : 'bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700',
                        ]"
                    >
                        <svg
                            v-if="isProcessing"
                            class="mr-2 inline h-4 w-4 animate-spin"
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
                        {{ isProcessing ? 'Saving...' : 'Save Changes' }}
                    </button>

                    <button
                        @click="cancelEdit"
                        :disabled="isProcessing"
                        class="rounded-lg bg-gray-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Share Modal -->
        <div
            v-if="shareModalLocation"
            class="bg-opacity-50 fixed inset-0 z-[1005] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click="closeShareModal"
        >
            <div
                class="mx-4 w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
                @click.stop
            >
                <div class="mb-4 flex items-center">
                    <div
                        class="mr-4 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100"
                    >
                        <svg
                            class="h-6 w-6 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Share Collection
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ shareModalLocation.name }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Share Link
                    </label>
                    <div class="flex rounded-lg border border-gray-300">
                        <input
                            ref="shareUrlInput"
                            :value="shareUrl"
                            readonly
                            class="flex-1 rounded-l-lg border-0 bg-gray-50 px-3 py-2 text-sm text-gray-600 focus:ring-0"
                        />
                        <button
                            @click="copyShareUrl"
                            class="rounded-r-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            <svg
                                v-if="!copied"
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-4 w-4 text-green-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Expires:
                        {{ formatDate(shareModalLocation.share_expires_at) }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <button
                        @click="copyShareUrl"
                        class="flex-1 rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-green-700"
                    >
                        <svg
                            class="mr-2 inline h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                            />
                        </svg>
                        {{ copied ? 'Copied!' : 'Copy Link' }}
                    </button>

                    <button
                        @click="closeShareModal"
                        class="rounded-lg bg-gray-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Extend Expiry Modal -->
        <div
            v-if="extendModalLocation"
            class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click="closeExtendModal"
        >
            <div
                class="mx-4 w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
                @click.stop
            >
                <div class="mb-4 flex items-center">
                    <div
                        class="mr-4 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100"
                    >
                        <svg
                            class="h-6 w-6 text-orange-600"
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
                        <h3 class="text-xl font-bold text-gray-900">
                            Extend Expiry
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ extendModalLocation.name }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Choose extension period
                    </label>

                    <!-- Quick Options -->
                    <div class="mb-3 grid grid-cols-3 gap-2">
                        <button
                            v-for="option in quickExtendOptions"
                            :key="option.days"
                            @click="selectedExtendDays = option.days"
                            :class="[
                                'rounded-lg border px-3 py-2 text-sm font-medium transition-colors',
                                selectedExtendDays === option.days
                                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                            ]"
                        >
                            {{ option.label }}
                        </button>
                    </div>

                    <!-- Custom Input -->
                    <div class="mb-3">
                        <label class="mb-1 block text-xs text-gray-600">
                            Or enter custom days
                        </label>
                        <input
                            v-model.number="selectedExtendDays"
                            type="number"
                            min="1"
                            max="365"
                            placeholder="Enter days"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200"
                        />
                    </div>

                    <!-- Preview -->
                    <div
                        v-if="selectedExtendDays"
                        class="rounded-lg bg-blue-50 p-3"
                    >
                        <p class="text-sm text-blue-700">
                            <strong>New expiry date:</strong><br />
                            {{ getNewExpiryDate() }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        @click="extendExpiry"
                        :disabled="
                            !selectedExtendDays ||
                            selectedExtendDays < 1 ||
                            isProcessing
                        "
                        :class="[
                            'flex-1 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                            !selectedExtendDays ||
                            selectedExtendDays < 1 ||
                            isProcessing
                                ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                : 'bg-gradient-to-r from-orange-500 to-orange-600 text-white hover:from-orange-600 hover:to-orange-700',
                        ]"
                    >
                        <svg
                            v-if="isProcessing"
                            class="mr-2 inline h-4 w-4 animate-spin"
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
                        {{ isProcessing ? 'Extending...' : 'Extend Expiry' }}
                    </button>

                    <button
                        @click="closeExtendModal"
                        :disabled="isProcessing"
                        class="rounded-lg bg-gray-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useNotyf } from '@/composables/useNotyf';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { EllipsisVertical, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Updated interface for location data
interface LocationData {
    id: string;
    name: string;
    share_code: string;
    share_expires_at: string;
    created_at: string;
    updated_at: string;
    data: {
        format?: string;
        // New multi-shape format
        shapes?: Array<{
            name: string;
            type: 'line' | 'polygon';
            points: Array<{ lat: number; lng: number }>;
            coordinates: number[][];
            bounds: {
                north: number;
                south: number;
                east: number;
                west: number;
            };
            area_km2?: number;
            length_km?: number;
        }>;
        bounds?: {
            north: number;
            south: number;
            east: number;
            west: number;
        };
        metadata?: any;
        // Old single polygon format (for backward compatibility)
        type?: string;
        points?: Array<{ lat: number; lng: number }>;
        coordinates?: number[][];
        area_km2?: number;
    };
    is_expired: boolean;
    // Multi-shape format properties
    total_area_km2?: number;
    total_length_km?: number;
    total_points?: number;
    shapes_count?: number;
    shape_types?: string[];
    format?: string;
    // Legacy format properties
    area_km2?: number;
    points_count?: number;
}

const props = defineProps<{
    locations: LocationData[];
    error?: string;
}>();

const notyf = useNotyf();
const openDropdown = ref<string | null>(null);
const processingLocation = ref<string | null>(null);
const editingLocation = ref<LocationData | null>(null);
const editName = ref('');
const isProcessing = ref(false);

// Share modal states
const shareModalLocation = ref<LocationData | null>(null);
const shareUrlInput = ref<HTMLInputElement>();
const copied = ref(false);

// Extend modal states
const extendModalLocation = ref<LocationData | null>(null);
const selectedExtendDays = ref<number>(7);
const quickExtendOptions = [
    { days: 7, label: '7 days' },
    { days: 30, label: '30 days' },
    { days: 90, label: '90 days' },
];

// Computed properties
const shareUrl = computed(() => {
    if (!shareModalLocation.value) return '';
    return `${window.location.origin}/locate?code=${shareModalLocation.value.share_code}`;
});

// Helper function to determine collection header styling
const getCollectionHeaderStyle = (location: LocationData) => {
    if (location.format === 'multi-shape') {
        // Check if has both lines and polygons
        if (
            location.shape_types?.includes('line') &&
            location.shape_types?.includes('polygon')
        ) {
            return 'border-purple-200 bg-gradient-to-r from-purple-50 to-indigo-50';
        } else if (location.shape_types?.includes('line')) {
            return 'border-green-200 bg-gradient-to-r from-green-50 to-emerald-50';
        } else {
            return 'border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50';
        }
    } else {
        // Legacy format
        return 'border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50';
    }
};

// Check if location is expired
const isExpired = (location: LocationData): boolean => {
    return new Date(location.share_expires_at) < new Date();
};

// Format date helper
const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get new expiry date preview
const getNewExpiryDate = (): string => {
    if (!selectedExtendDays.value) return '';
    const newDate = new Date();
    newDate.setDate(newDate.getDate() + selectedExtendDays.value);
    return formatDate(newDate.toISOString());
};

// Toggle dropdown
const toggleDropdown = (locationId: string) => {
    openDropdown.value = null;
    openDropdown.value = openDropdown.value === locationId ? null : locationId;
};
// View location
const viewLocation = (location: LocationData) => {
    const url = `/locate?code=${location.share_code}`;
    window.open(url, '_blank');
};

// Show share modal
const showShareModal = (location: LocationData) => {
    if (isExpired(location)) return;
    shareModalLocation.value = location;
    copied.value = false;
    openDropdown.value = null;
};

// Close share modal
const closeShareModal = () => {
    shareModalLocation.value = null;
    copied.value = false;
};

// Copy share URL
const copyShareUrl = async () => {
    if (!shareModalLocation.value) return;

    try {
        await navigator.clipboard.writeText(shareUrl.value);
        copied.value = true;
        notyf.success('Share link copied to clipboard!');

        // Reset copied state after 2 seconds
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch (error) {
        // Fallback for older browsers
        if (shareUrlInput.value) {
            shareUrlInput.value.select();
            document.execCommand('copy');
            copied.value = true;
            notyf.success('Share link copied to clipboard!');

            setTimeout(() => {
                copied.value = false;
            }, 2000);
        }
    }
};

// Show extend modal
const showExtendModal = (location: LocationData) => {
    extendModalLocation.value = location;
    selectedExtendDays.value = 7; // Default to 7 days
    openDropdown.value = null;
};

// Close extend modal
const closeExtendModal = () => {
    extendModalLocation.value = null;
    selectedExtendDays.value = 7;
    isProcessing.value = false;
};

// Extend expiry
const extendExpiry = async () => {
    if (
        !extendModalLocation.value ||
        !selectedExtendDays.value ||
        selectedExtendDays.value < 1 ||
        isProcessing.value
    ) {
        return;
    }

    isProcessing.value = true;

    try {
        const response = await axios.post(
            `/locations/${extendModalLocation.value.id}/extend-expiry`,
            {
                days: selectedExtendDays.value,
            },
        );

        if (response.data.success) {
            // Update the location's expiry status
            extendModalLocation.value.share_expires_at =
                response.data.new_expiry;
            extendModalLocation.value.is_expired = false;

            // Update the location in the list
            const locationIndex = props.locations.findIndex(
                (loc) => loc.id === extendModalLocation.value!.id,
            );
            if (locationIndex !== -1) {
                props.locations[locationIndex].share_expires_at =
                    response.data.new_expiry;
                props.locations[locationIndex].is_expired = false;
            }

            notyf.success(response.data.message);
            closeExtendModal();
        }
    } catch (error: any) {
        console.error('Error extending expiry:', error);
        notyf.error(error.response?.data?.message || 'Failed to extend expiry');
    } finally {
        isProcessing.value = false;
    }
};

// Edit location name
const editLocation = (location: LocationData) => {
    editingLocation.value = location;
    editName.value = location.name;
    openDropdown.value = null;
};

// Save edited name
const saveEdit = async () => {
    if (!editingLocation.value || !editName.value.trim() || isProcessing.value)
        return;

    isProcessing.value = true;

    try {
        const response = await axios.put(
            `/locations/${editingLocation.value.id}`,
            {
                name: editName.value.trim(),
            },
        );

        if (response.data.success) {
            // Update the location in the list
            const locationIndex = props.locations.findIndex(
                (loc) => loc.id === editingLocation.value!.id,
            );
            if (locationIndex !== -1) {
                props.locations[locationIndex].name = editName.value.trim();
            }

            notyf.success('Collection name updated successfully!');
            cancelEdit();
        }
    } catch (error: any) {
        console.error('Error updating collection name:', error);
        notyf.error(
            error.response?.data?.message || 'Failed to update collection name',
        );
    } finally {
        isProcessing.value = false;
    }
};

// Cancel edit
const cancelEdit = () => {
    editingLocation.value = null;
    editName.value = '';
    isProcessing.value = false;
};

// Delete location
const deleteLocation = async (location: LocationData) => {
    if (processingLocation.value === location.id) return;

    const confirmDelete = confirm(
        `Are you sure you want to delete "${location.name}"? This action cannot be undone.`,
    );

    if (!confirmDelete) return;

    processingLocation.value = location.id;
    openDropdown.value = null;

    try {
        const response = await axios.delete(`/locations/${location.id}`);

        if (response.data.success) {
            // Remove the location from the list
            const locationIndex = props.locations.findIndex(
                (loc) => loc.id === location.id,
            );
            if (locationIndex !== -1) {
                props.locations.splice(locationIndex, 1);
            }

            notyf.success(response.data.message);
        }
    } catch (error: any) {
        console.error('Error deleting location:', error);
        notyf.error(
            error.response?.data?.message || 'Failed to delete collection',
        );
    } finally {
        processingLocation.value = null;
    }
};
</script>
