<template>
    <AppLayout>
        <div class=" p-4">
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
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Create Shapes
                            </h1>
                            <p class="mt-1 text-gray-600">
                                Design custom lines and polygons by clicking on
                                the map or entering coordinates manually
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                <!-- Map Container with Enhanced Controls -->
                <div
                    class="col-span-2 flex flex-col space-y-4 overflow-visible lg:order-2"
                >
                    <!-- Map Type Selector -->
                    <div class="w-full">
                        <div class="relative">
                            <select
                                id="mapSelect"
                                v-model="selectedMapType"
                                @change="changeMapType"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            >
                                <option value="osm">🗺️ OpenStreetMap</option>
                                <option value="satellite">
                                    🛰️ Satellite View
                                </option>
                                <option value="terrain">🏔️ Terrain View</option>
                            </select>
                        </div>
                    </div>
                    <div
                        class="relative overflow-visible rounded-2xl bg-white shadow-lg"
                    >
                        <!-- Floating Control Panel -->
                        <div
                            class="absolute top-1 z-[501] flex w-full space-x-3 overflow-x-scroll ps-13 pe-4 sm:top-4 sm:right-4 sm:block sm:w-auto sm:space-y-3 sm:space-x-0 sm:overflow-visible sm:p-0"
                        >
                            <!-- Primary Controls -->
                            <div
                                class="flex h-18 space-x-2 rounded-xl border border-gray-200 bg-white/95 p-3 text-nowrap shadow-lg backdrop-blur-sm sm:block sm:h-auto sm:space-y-2 sm:space-x-0 sm:text-wrap"
                            >
                                <!-- Drawing Mode Buttons -->
                                <button
                                    v-show="!showCoordinateInput"
                                    @click="toggleDrawingMode('line')"
                                    :class="[
                                        'flex w-full transform items-center justify-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 hover:scale-105',
                                        isPolygonMode &&
                                        currentShapeType === 'line'
                                            ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md'
                                            : 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md hover:from-green-600 hover:to-green-700',
                                    ]"
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
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>
                                    {{
                                        isPolygonMode &&
                                        currentShapeType === 'line'
                                            ? 'Cancel Line'
                                            : 'Draw Line'
                                    }}
                                </button>

                                <button
                                    v-show="!showCoordinateInput"
                                    @click="toggleDrawingMode('polygon')"
                                    :class="[
                                        'flex w-full transform items-center justify-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 hover:scale-105',
                                        isPolygonMode &&
                                        currentShapeType === 'polygon'
                                            ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md'
                                            : 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md hover:from-blue-600 hover:to-blue-700',
                                    ]"
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
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.382v10.764a1 1 0 01-.553.894L15 18l-6-3z"
                                        />
                                    </svg>
                                    {{
                                        isPolygonMode &&
                                        currentShapeType === 'polygon'
                                            ? 'Cancel Polygon'
                                            : 'Draw Polygon'
                                    }}
                                </button>

                                <button
                                    v-show="!isPolygonMode"
                                    @click="toggleCoordinateInput"
                                    :class="[
                                        'flex w-full transform items-center justify-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 hover:scale-105',
                                        showCoordinateInput
                                            ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md'
                                            : 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-md hover:from-indigo-600 hover:to-indigo-700',
                                    ]"
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
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                        />
                                    </svg>
                                    {{
                                        showCoordinateInput
                                            ? 'Cancel Input'
                                            : 'Enter Coordinates'
                                    }}
                                </button>
                                <button
                                    v-if="currentShapePoints.length > 0"
                                    @click="removeLastPoint"
                                    class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 text-sm font-medium text-white shadow-md transition-all duration-200 hover:scale-105 hover:from-orange-600 hover:to-orange-700"
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
                                            d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4z"
                                        />
                                    </svg>
                                    Undo Last Point
                                </button>
                            </div>

                            <!-- Secondary Controls -->
                            <div
                                class="flex h-18 space-x-2 rounded-xl border border-gray-200 bg-white/95 p-3 text-nowrap shadow-lg backdrop-blur-sm sm:block sm:h-auto sm:space-y-2 sm:space-x-0 sm:text-wrap"
                                v-show="!isPolygonMode && !showCoordinateInput"
                            >
                                <button
                                    @click="moveToCurrentLocation"
                                    :disabled="isLoadingLocation"
                                    class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 text-sm font-medium text-white shadow-md transition-all duration-200 hover:scale-105 hover:from-green-600 hover:to-green-700 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <svg
                                        v-if="!isLoadingLocation"
                                        class="mr-2 h-4 w-4"
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
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    {{
                                        isLoadingLocation
                                            ? 'Locating...'
                                            : 'My Location'
                                    }}
                                </button>

                                <button
                                    @click="clearAll"
                                    class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-gray-500 to-gray-600 px-4 py-3 text-sm font-medium text-white shadow-md transition-all duration-200 hover:scale-105 hover:from-gray-600 hover:to-gray-700"
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    Clear All
                                </button>

                                <button
                                    @click="logDataForAPI"
                                    class="flex w-full transform items-center justify-center rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-3 text-sm font-medium text-white shadow-md transition-all duration-200 hover:scale-105 hover:from-purple-600 hover:to-purple-700"
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
                                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                    Debug Data
                                </button>
                            </div>
                        </div>

                        <!-- Shape Creation Panel -->
                        <div
                            class="absolute right-0 bottom-0 z-[502] flex w-full flex-col sm:w-auto"
                        >
                            <!-- Manual Coordinate Input Panel -->
                            <div
                                id="manual-coordinate-form"
                                class="w-full p-1 sm:w-fit"
                            >
                                <div
                                    v-show="showCoordinateInput"
                                    class="sm:w-sm"
                                >
                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-200 bg-white/95 shadow-lg backdrop-blur-sm"
                                    >
                                        <!-- Panel Header -->
                                        <div
                                            class="border-b border-indigo-200 bg-gradient-to-r from-indigo-50 to-purple-50 p-4"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div class="flex items-center">
                                                    <div
                                                        class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100"
                                                    >
                                                        <svg
                                                            class="h-4 w-4 text-indigo-600"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                                            />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3
                                                            class="text-lg font-semibold text-gray-900"
                                                        >
                                                            Manual Coordinates
                                                        </h3>
                                                        <p
                                                            class="text-sm text-gray-600"
                                                        >
                                                            Enter precise
                                                            coordinates
                                                        </p>
                                                    </div>
                                                </div>
                                                <button
                                                    @click="
                                                        hideCoordinateInput =
                                                            !hideCoordinateInput;
                                                        hideShapeCreation = true;
                                                    "
                                                    class="rounded-lg p-2 transition-colors hover:bg-indigo-100"
                                                >
                                                    <ChevronUp
                                                        v-if="
                                                            !hideCoordinateInput
                                                        "
                                                        class="h-5 w-5 text-gray-500"
                                                    />
                                                    <ChevronDown
                                                        v-else
                                                        class="h-5 w-5 text-gray-500"
                                                    />
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Panel Content -->
                                        <div
                                            v-show="!hideCoordinateInput"
                                            class="space-y-4 p-4"
                                        >
                                            <!-- Format Tabs -->
                                            <div
                                                class="flex rounded-lg border border-gray-200 bg-gray-50 p-1"
                                            >
                                                <button
                                                    @click="
                                                        coordinateFormat = 'DD'
                                                    "
                                                    :class="[
                                                        'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200',
                                                        coordinateFormat ===
                                                        'DD'
                                                            ? 'bg-indigo-500 text-white shadow-sm'
                                                            : 'text-gray-600 hover:bg-gray-100',
                                                    ]"
                                                >
                                                    DD
                                                </button>
                                                <button
                                                    @click="
                                                        coordinateFormat = 'DMS'
                                                    "
                                                    :class="[
                                                        'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200',
                                                        coordinateFormat ===
                                                        'DMS'
                                                            ? 'bg-indigo-500 text-white shadow-sm'
                                                            : 'text-gray-600 hover:bg-gray-100',
                                                    ]"
                                                >
                                                    DMS
                                                </button>
                                                <button
                                                    @click="
                                                        coordinateFormat =
                                                            'STRING'
                                                    "
                                                    :class="[
                                                        'flex-1 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200',
                                                        coordinateFormat ===
                                                        'STRING'
                                                            ? 'bg-indigo-500 text-white shadow-sm'
                                                            : 'text-gray-600 hover:bg-gray-100',
                                                    ]"
                                                >
                                                    Parse
                                                </button>
                                            </div>

                                            <!-- DD Format Input -->
                                            <div
                                                v-show="
                                                    coordinateFormat === 'DD'
                                                "
                                                class="grid gap-3"
                                            >
                                                <div>
                                                    <label
                                                        class="mb-2 block text-sm font-medium text-gray-700"
                                                    >
                                                        Latitude
                                                    </label>
                                                    <input
                                                        v-model.number="
                                                            manualInput.lat
                                                        "
                                                        inputmode="decimal"
                                                        type="text"
                                                        step="any"
                                                        placeholder="51.505"
                                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                    />
                                                    <p
                                                        class="mt-1 text-xs text-gray-500"
                                                    >
                                                        Range: -90 to 90
                                                    </p>
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-2 block text-sm font-medium text-gray-700"
                                                    >
                                                        Longitude
                                                    </label>
                                                    <input
                                                        v-model.number="
                                                            manualInput.lng
                                                        "
                                                        inputmode="decimal"
                                                        type="text"
                                                        step="any"
                                                        placeholder="-0.09"
                                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                    />
                                                    <p
                                                        class="mt-1 text-xs text-gray-500"
                                                    >
                                                        Range: -180 to 180
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- DMS Format Input -->
                                            <div
                                                v-show="
                                                    coordinateFormat === 'DMS'
                                                "
                                                class="space-y-4"
                                            >
                                                <!-- Latitude DMS -->
                                                <div>
                                                    <label
                                                        class="mb-2 block text-sm font-medium text-gray-700"
                                                    >
                                                        Latitude
                                                    </label>
                                                    <div
                                                        class="grid grid-cols-4 gap-2"
                                                    >
                                                        <input
                                                            v-model.number="
                                                                dmsInput.latDeg
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="90"
                                                            placeholder="1"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <input
                                                            v-model.number="
                                                                dmsInput.latMin
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="59"
                                                            placeholder="50"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <input
                                                            v-model.number="
                                                                dmsInput.latSec
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="59.999"
                                                            step="0.001"
                                                            placeholder="39.0"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <select
                                                            v-model="
                                                                dmsInput.latDir
                                                            "
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        >
                                                            <option value="N">
                                                                N
                                                            </option>
                                                            <option value="S">
                                                                S
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <p
                                                        class="mt-1 text-xs text-gray-500"
                                                    >
                                                        Degrees, Minutes,
                                                        Seconds, Direction
                                                    </p>
                                                </div>

                                                <!-- Longitude DMS -->
                                                <div>
                                                    <label
                                                        class="mb-2 block text-sm font-medium text-gray-700"
                                                    >
                                                        Longitude
                                                    </label>
                                                    <div
                                                        class="grid grid-cols-4 gap-2"
                                                    >
                                                        <input
                                                            v-model.number="
                                                                dmsInput.lngDeg
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="180"
                                                            placeholder="110"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <input
                                                            v-model.number="
                                                                dmsInput.lngMin
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="59"
                                                            placeholder="0"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <input
                                                            v-model.number="
                                                                dmsInput.lngSec
                                                            "
                                                            inputmode="decimal"
                                                            type="text"
                                                            min="0"
                                                            max="59.999"
                                                            step="0.001"
                                                            placeholder="18.3"
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        />
                                                        <select
                                                            v-model="
                                                                dmsInput.lngDir
                                                            "
                                                            class="rounded-lg border border-gray-300 bg-gray-50 px-2 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                        >
                                                            <option value="E">
                                                                E
                                                            </option>
                                                            <option value="W">
                                                                W
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <p
                                                        class="mt-1 text-xs text-gray-500"
                                                    >
                                                        Degrees, Minutes,
                                                        Seconds, Direction
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- String Parse Input -->
                                            <div
                                                v-show="
                                                    coordinateFormat ===
                                                    'STRING'
                                                "
                                                class="space-y-3"
                                            >
                                                <!-- Format Examples -->
                                                <div
                                                    class="rounded-lg border border-blue-200 bg-blue-50 p-2"
                                                >
                                                    <p
                                                        class="mb-1 text-xs font-medium text-blue-800"
                                                    >
                                                        Supported formats:
                                                    </p>
                                                    <div
                                                        class="space-y-1 text-xs text-blue-700"
                                                    >
                                                        <div>
                                                            <strong
                                                                >DMS:</strong
                                                            >
                                                            1°50'39.0"S
                                                            110°00'18.3"E
                                                        </div>
                                                        <div>
                                                            <strong>DD:</strong>
                                                            -1.844157,
                                                            110.005072
                                                        </div>
                                                        <div>
                                                            <strong
                                                                >DD with
                                                                direction:</strong
                                                            >
                                                            1.840707°S,
                                                            110.002740°E
                                                        </div>
                                                        <div>
                                                            <strong
                                                                >Space
                                                                separated:</strong
                                                            >
                                                            1.840707°S
                                                            110.002740°E
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label
                                                        class="mb-2 block text-sm font-medium text-gray-700"
                                                    >
                                                        Coordinate String
                                                    </label>
                                                    <textarea
                                                        ref="coordinateTextarea"
                                                        v-model="
                                                            coordinateInput
                                                        "
                                                        rows="3"
                                                        placeholder="Examples:&#10;1°50'39.0&quot;S 110°00'18.3&quot;E&#10;-1.844157, 110.005072"
                                                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200"
                                                    ></textarea>
                                                    <p
                                                        class="mt-1 text-xs text-gray-500"
                                                    >
                                                        Supports DMS and DD
                                                        formats, separated by
                                                        comma
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput('°')
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    °
                                                </button>
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput(`'`)
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    '
                                                </button>
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput(
                                                            '&quot;',
                                                        )
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    "
                                                </button>
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput('.')
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    .
                                                </button>
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput(',')
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    ,
                                                </button>
                                                <button
                                                    @mousedown.prevent
                                                    @click="
                                                        addSymbolToInput('-')
                                                    "
                                                    class="h-8 w-8 cursor-pointer rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    -
                                                </button>
                                            </div>
                                            <div class="flex gap-2">
                                                <button
                                                    @click="addPointFromInput"
                                                    :disabled="
                                                        !isValidCoordinateInput
                                                    "
                                                    :class="[
                                                        'flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200',
                                                        isValidCoordinateInput
                                                            ? 'transform bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-sm hover:scale-105'
                                                            : 'cursor-not-allowed bg-gray-100 text-gray-400',
                                                    ]"
                                                >
                                                    <svg
                                                        class="mr-1 inline h-3 w-3"
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
                                                    Add Point
                                                </button>

                                                <button
                                                    @click="clearManualInput"
                                                    class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    Clear
                                                </button>
                                            </div>

                                            <div
                                                v-if="manualInputError"
                                                class="rounded-lg border border-red-200 bg-red-50 p-2"
                                            >
                                                <p class="text-xs text-red-700">
                                                    {{ manualInputError }}
                                                </p>
                                            </div>

                                            <!-- Instructions -->
                                            <div
                                                class="rounded-lg border border-blue-200 bg-blue-50 p-3"
                                            >
                                                <p
                                                    class="text-xs text-blue-800"
                                                >
                                                    <svg
                                                        class="mr-1 inline h-3 w-3"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    Choose a format above to
                                                    enter coordinates. This will
                                                    automatically enable polygon
                                                    mode.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Shape Creation Panel -->
                            <div class="w-full p-1 sm:w-fit">
                                <div
                                    v-if="
                                        isPolygonMode ||
                                        currentShapePoints.length > 0 ||
                                        showCoordinateInput
                                    "
                                    class="sm:w-sm"
                                >
                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-200 bg-white/95 shadow-lg backdrop-blur-sm"
                                    >
                                        <!-- Panel Header -->
                                        <div
                                            :class="[
                                                'border-b p-4',
                                                currentShapeType === 'line'
                                                    ? 'border-green-200 bg-gradient-to-r from-green-50 to-emerald-50'
                                                    : 'border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50',
                                            ]"
                                        >
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <div>
                                                    <h3
                                                        class="text-lg font-semibold text-gray-900"
                                                    >
                                                        Confirm
                                                        {{
                                                            currentShapeType ===
                                                            'line'
                                                                ? 'Line'
                                                                : 'Polygon'
                                                        }}
                                                    </h3>
                                                    <p
                                                        class="text-sm text-gray-600"
                                                    >
                                                        {{
                                                            currentShapePoints.length
                                                        }}
                                                        points added
                                                        <span
                                                            v-if="
                                                                currentShapeType ===
                                                                    'line' &&
                                                                currentShapePoints.length >=
                                                                    2
                                                            "
                                                        >
                                                            • Ready to finish
                                                        </span>
                                                        <span
                                                            v-else-if="
                                                                currentShapeType ===
                                                                    'polygon' &&
                                                                currentShapePoints.length >=
                                                                    3
                                                            "
                                                        >
                                                            • Ready to finish
                                                        </span>
                                                    </p>
                                                </div>
                                                <button
                                                    @click="
                                                        ((hideShapeCreation =
                                                            !hideShapeCreation),
                                                        (hideCoordinateInput = true))
                                                    "
                                                    class="rounded-lg p-2 transition-colors hover:bg-gray-100"
                                                >
                                                    <ChevronUp
                                                        v-if="
                                                            !hideShapeCreation
                                                        "
                                                        class="h-5 w-5 text-gray-500"
                                                    />
                                                    <ChevronDown
                                                        v-else
                                                        class="h-5 w-5 text-gray-500"
                                                    />
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Panel Content -->
                                        <div
                                            v-show="!hideShapeCreation"
                                            class="space-y-4 p-4"
                                        >
                                            <!-- Shape Name Input -->
                                            <div>
                                                <label
                                                    class="mb-2 block text-sm font-medium text-gray-700"
                                                >
                                                    {{
                                                        currentShapeType ===
                                                        'line'
                                                            ? 'Line'
                                                            : 'Polygon'
                                                    }}
                                                    Name
                                                </label>
                                                <input
                                                    v-model="currentShapeName"
                                                    type="text"
                                                    :placeholder="
                                                        currentShapeType ===
                                                        'line'
                                                            ? 'e.g., Road, Path, Route...'
                                                            : 'e.g., Park Area, Building Zone...'
                                                    "
                                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-200"
                                                />
                                            </div>

                                            <!-- Instructions -->
                                            <div
                                                class="rounded-lg border border-blue-200 bg-blue-50 p-3"
                                            >
                                                <p
                                                    class="text-sm text-blue-800"
                                                >
                                                    <svg
                                                        class="mr-1 inline h-4 w-4"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    Click on the map to add
                                                    points. You need at least
                                                    {{ minPointsRequired }}
                                                    points to create a
                                                    {{ currentShapeType }}.
                                                </p>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex gap-2">
                                                <button
                                                    @click="finishShape"
                                                    :disabled="
                                                        currentShapePoints.length <
                                                        minPointsRequired
                                                    "
                                                    :class="[
                                                        'flex-1 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                                                        currentShapePoints.length >=
                                                        minPointsRequired
                                                            ? 'transform bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md hover:scale-105'
                                                            : 'cursor-not-allowed bg-gray-100 text-gray-400',
                                                    ]"
                                                >
                                                    {{
                                                        currentShapePoints.length >=
                                                        minPointsRequired
                                                            ? '✓ Complete'
                                                            : `Need ${minPointsRequired - currentShapePoints.length} more`
                                                    }}
                                                </button>

                                                <button
                                                    :disabled="
                                                        currentShapePoints.length <
                                                        1
                                                    "
                                                    :class="[
                                                        'rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                                                        currentShapePoints.length >=
                                                        1
                                                            ? 'transform bg-red-700 text-white shadow-md hover:scale-105 hover:bg-red-200'
                                                            : 'cursor-not-allowed bg-gray-100 text-gray-400',
                                                    ]"
                                                    @click="cancelShape"
                                                >
                                                    Clear
                                                </button>
                                            </div>

                                            <!-- Current Points List -->
                                            <div
                                                v-if="
                                                    currentShapePoints.length >
                                                    0
                                                "
                                                class="max-h-32 overflow-y-auto"
                                            >
                                                <h4
                                                    class="mb-2 text-sm font-medium text-gray-700"
                                                >
                                                    Points:
                                                </h4>
                                                <div class="space-y-1">
                                                    <div
                                                        v-for="(
                                                            point, index
                                                        ) in currentShapePoints"
                                                        :key="index"
                                                        class="flex items-center justify-between rounded-lg bg-gray-50 p-2 text-xs"
                                                    >
                                                        <span
                                                            class="font-medium text-gray-600"
                                                        >
                                                            {{ index + 1 }}.
                                                        </span>
                                                        <span
                                                            class="font-mono text-gray-800"
                                                        >
                                                            {{
                                                                point.lat.toFixed(
                                                                    4,
                                                                )
                                                            }},
                                                            {{
                                                                point.lng.toFixed(
                                                                    4,
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            @click="focusOnMap = true"
                            v-show="!focusOnMap"
                            class="absolute top-0 z-[700] flex h-[500px] w-full cursor-pointer items-center justify-center bg-black/40 text-white backdrop-blur-[1px] transition-opacity duration-200"
                        >
                            <div class="text-center">
                                <h2 class="mb-2 text-lg font-bold">
                                    Click or Tap to interact with the map
                                </h2>
                                <p class="text-sm opacity-80">
                                    Click left side of the map to unfocused
                                </p>
                            </div>
                        </div>
                        <div
                            v-show="showCoordinateInput"
                            :class="[
                                currentPolygonPoints.length > 0
                                    ? ''
                                    : 'backdrop-blur-[1px]',
                            ]"
                            class="absolute top-0 z-[500] flex h-[500px] w-full cursor-pointer items-center justify-center transition-opacity duration-200"
                        ></div>
                        <button
                            v-show="focusOnMap"
                            class="absolute bottom-2 left-2 z-[501] rounded-lg bg-white/90 px-3 py-2 text-sm font-medium text-gray-700 shadow-md backdrop-blur-sm transition-colors hover:bg-gray-100"
                            @click="focusOnMap = false"
                        >
                            Unfocus
                        </button>
                        <!-- Map Container -->
                        <div
                            ref="mapContainer"
                            class="h-[500px] w-full bg-gray-100"
                        ></div>
                    </div>
                </div>

                <!-- Shapes Display Panel -->
                <div
                    class="col-span-2 h-full lg:col-span-1 lg:flex lg:flex-col"
                >
                    <div
                        class="grow overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
                    >
                        <div
                            class="border-b border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 p-6"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-green-100"
                                    >
                                        <svg
                                            class="h-5 w-5 text-green-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            Created Shapes
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ shapes.length }} shape{{
                                                shapes.length !== 1 ? 's' : ''
                                            }}
                                            ready
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800"
                                >
                                    {{ shapes.length }}
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div
                                v-if="shapes.length === 0"
                                class="py-12 text-center"
                            >
                                <svg
                                    class="mx-auto mb-4 h-16 w-16 text-gray-300"
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
                                <h4
                                    class="mb-2 text-lg font-medium text-gray-900"
                                >
                                    No shapes created yet
                                </h4>
                                <p class="text-gray-600">
                                    Start by clicking "Draw Line" or "Draw
                                    Polygon" and adding points
                                </p>
                            </div>

                            <div
                                v-else
                                class="max-h-80 space-y-4 overflow-y-auto"
                            >
                                <div
                                    v-for="(shape, index) in shapes"
                                    :key="shape.id"
                                    :class="[
                                        'rounded-xl border border-gray-200 p-4 transition-shadow hover:shadow-md',
                                        shape.type === 'line'
                                            ? 'bg-gradient-to-r from-green-50 to-emerald-100'
                                            : 'bg-gradient-to-r from-blue-50 to-indigo-100',
                                    ]"
                                >
                                    <div
                                        class="mb-3 flex items-center justify-between"
                                    >
                                        <div class="flex items-center">
                                            <div
                                                :class="[
                                                    'mr-3 flex h-8 w-8 items-center justify-center rounded-lg text-sm font-bold',
                                                    shape.type === 'line'
                                                        ? 'bg-green-200 text-green-700'
                                                        : 'bg-blue-200 text-blue-700',
                                                ]"
                                            >
                                                <svg
                                                    v-if="shape.type === 'line'"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                                    />
                                                </svg>
                                                <svg
                                                    v-else
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2l6 3 5.447-2.724A1 1 0 0121 3.382v10.764a1 1 0 01-.553.894L15 18l-6-3z"
                                                    />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4
                                                    class="font-semibold text-gray-900"
                                                >
                                                    {{ shape.name }}
                                                </h4>
                                                <p
                                                    class="text-sm text-gray-600"
                                                >
                                                    {{ shape.points.length }}
                                                    points
                                                    <span
                                                        v-if="
                                                            shape.type ===
                                                            'polygon'
                                                        "
                                                    >
                                                        •
                                                        {{
                                                            shape.area?.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                        km²
                                                    </span>
                                                    <span
                                                        v-else-if="
                                                            shape.type ===
                                                            'line'
                                                        "
                                                    >
                                                        •
                                                        {{
                                                            shape.length?.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                        km
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button
                                                @click="visitShape(shape)"
                                                :class="[
                                                    'flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                                    shape.type === 'line'
                                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                        : 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                                                ]"
                                            >
                                                <ArrowRight
                                                    class="mr-1 h-4 w-4"
                                                />
                                                View
                                            </button>
                                            <button
                                                @click="removeShape(index)"
                                                class="flex items-center rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-200"
                                            >
                                                <Trash class="mr-1 h-4 w-4" />
                                                Remove
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Points Preview -->
                                    <div
                                        class="rounded-lg bg-white p-3 text-xs text-gray-600"
                                    >
                                        <div class="grid grid-cols-2 gap-2">
                                            <div
                                                v-for="(
                                                    point, pointIndex
                                                ) in shape.points.slice(0, 4)"
                                                :key="pointIndex"
                                                class="font-mono"
                                            >
                                                {{ pointIndex + 1 }}.
                                                {{ point.lat.toFixed(4) }},
                                                {{ point.lng.toFixed(4) }}
                                            </div>
                                        </div>
                                        <div
                                            v-if="shape.points.length > 4"
                                            class="mt-2 text-center text-gray-500"
                                        >
                                            ... and
                                            {{ shape.points.length - 4 }} more
                                            points
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 text-center">
                        <button
                            @click="openSaveModal"
                            :disabled="shapes.length === 0 || isSaving"
                            :class="[
                                'w-full transform rounded-xl px-8 py-4 text-lg font-semibold transition-all duration-200',
                                shapes.length === 0 || isSaving
                                    ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                    : 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg hover:scale-105 hover:shadow-xl',
                            ]"
                        >
                            <svg
                                class="mr-2 inline h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"
                                />
                            </svg>
                            {{
                                shapes.length === 0
                                    ? 'Create at least one shape to save'
                                    : `Save ${shapes.length} Shape${shapes.length !== 1 ? 's' : ''}`
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Modal -->
        <div
            v-if="showSaveModal"
            class="bg-opacity-50 fixed inset-0 z-[1001] flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click="showSaveModal = false"
        >
            <div
                class="mx-4 w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
                @click.stop
            >
                <h3 class="mb-4 text-xl font-bold text-gray-900">
                    Save Shape Collection
                </h3>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Collection Name
                    </label>
                    <input
                        v-model="collectionName"
                        type="text"
                        placeholder="e.g., Survey Area, Route Plan..."
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm transition-colors focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-200"
                        @keydown.enter="saveShapes"
                    />
                </div>

                <div
                    class="mb-4 rounded-lg border border-blue-200 bg-blue-50 p-3"
                >
                    <p class="text-sm text-blue-800">
                        This will save {{ shapes.length }} shape{{
                            shapes.length !== 1 ? 's' : ''
                        }}:
                    </p>
                    <ul class="mt-2 text-xs text-blue-700">
                        <li v-for="shape in shapes" :key="shape.id">
                            • {{ shape.name }} ({{ shape.type }})
                        </li>
                    </ul>
                </div>

                <div class="flex gap-3">
                    <button
                        @click="saveShapes"
                        :disabled="!collectionName.trim() || isSaving"
                        :class="[
                            'flex-1 rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200',
                            !collectionName.trim() || isSaving
                                ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                : 'bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700',
                        ]"
                    >
                        <svg
                            v-if="isSaving"
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
                        {{ isSaving ? 'Saving...' : 'Save Collection' }}
                    </button>

                    <button
                        @click="showSaveModal = false"
                        :disabled="isSaving"
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
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';
// Fix for default markers in Leaflet with Vite
import axios from 'axios';
import markerRetina from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import { ArrowRight, ChevronDown, ChevronUp, Trash } from 'lucide-vue-next';

// Notyf for notifications
const notyf = useNotyf();

// Fix leaflet default icon issue
const DefaultIcon = L.icon({
    iconUrl: markerIcon,
    iconRetinaUrl: markerRetina,
    shadowUrl: markerShadow,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
});
L.Marker.prototype.options.icon = DefaultIcon;

defineOptions({
    components: {
        AppLayout,
    },
});

// Types
interface PolygonPoint {
    lat: number;
    lng: number;
}

interface PolygonData {
    name?: string;
    points: PolygonPoint[];
    polygon: L.Polygon;
    area: number;
}
interface ShapePoint {
    lat: number;
    lng: number;
}

interface ShapeData {
    id: string;
    name: string;
    type: 'line' | 'polygon';
    points: ShapePoint[];
    shape: L.Polygon | L.Polyline; // Updated to support both
    area?: number; // Optional for lines
    length?: number; // For lines
}
// Reactive variables
const mapContainer = ref<HTMLElement>();
const map = ref<L.Map | null>(null);
const selectedMapType = ref('osm');
const isMarkerMode = ref(false);
const isPolygonMode = ref(false);
const isLoadingLocation = ref(false);
const markers = ref<Array<{ lat: number; lng: number; marker: L.Marker }>>([]);
const polygons = ref<PolygonData[]>([]);
const currentLocationMarker = ref<L.Marker | null>(null);
const currentLocationCircle = ref<L.Circle | null>(null);
const hideShapeCreation = ref(false); // Add this missing variable
const isSaving = ref(false);
const showCoordinateInput = ref(false);
const hideCoordinateInput = ref(false);
const coordinateFormat = ref<'DD' | 'DMS' | 'STRING'>('DD');
const dmsInput = ref({
    latDeg: null as number | null,
    latMin: null as number | null,
    latSec: null as number | null,
    latDir: 'N' as 'N' | 'S',
    lngDeg: null as number | null,
    lngMin: null as number | null,
    lngSec: null as number | null,
    lngDir: 'E' as 'E' | 'W',
});
const coordinateInput = ref('');
const focusOnMap = ref(false);
const coordinateTextarea = ref<HTMLTextAreaElement>();
const showSaveModal = ref(false);
const collectionName = ref('');
const shapes = ref<ShapeData[]>([]);
const currentShapePoints = ref<ShapePoint[]>([]);
const currentShapeMarkers = ref<L.Marker[]>([]);
const currentShapeLines = ref<L.Polyline[]>([]);
const currentShapeName = ref('');
const currentShapeType = ref<'line' | 'polygon'>('polygon');

// Polygon creation
const currentPolygonPoints = ref<PolygonPoint[]>([]);
const currentPolygonMarkers = ref<L.Marker[]>([]);
const currentPolygonLines = ref<L.Polyline[]>([]);
const currentPolygonName = ref('');

// Manual coordinate input
const manualInput = ref({
    lat: null as number | null,
    lng: null as number | null,
});
const manualInputError = ref('');

// Computed properties
const minPointsRequired = computed(() => {
    return currentShapeType.value === 'line' ? 2 : 3;
});

const isValidCoordinateInput = computed(() => {
    if (coordinateFormat.value === 'DD') {
        const { lat, lng } = manualInput.value;
        return (
            lat !== null &&
            lng !== null &&
            lat >= -90 &&
            lat <= 90 &&
            lng >= -180 &&
            lng <= 180
        );
    } else if (coordinateFormat.value === 'DMS') {
        const { latDeg, latMin, latSec, lngDeg, lngMin, lngSec } =
            dmsInput.value;
        return (
            latDeg !== null &&
            latMin !== null &&
            latSec !== null &&
            lngDeg !== null &&
            lngMin !== null &&
            lngSec !== null &&
            latDeg >= 0 &&
            latDeg <= 90 &&
            latMin >= 0 &&
            latMin < 60 &&
            latSec >= 0 &&
            latSec < 60 &&
            lngDeg >= 0 &&
            lngDeg <= 180 &&
            lngMin >= 0 &&
            lngMin < 60 &&
            lngSec >= 0 &&
            lngSec < 60
        );
    } else {
        // String input format
        return (
            coordinateInput.value.trim() !== '' &&
            parseCoordinateString(coordinateInput.value) !== null
        );
    }
});

// Map layers
const mapLayers = {
    osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }),
    satellite: L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        {
            attribution: '© Esri',
        },
    ),
    terrain: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenTopoMap contributors',
    }),
};

// Initialize map
const initMap = async () => {
    if (!mapContainer.value) return;

    await nextTick();

    try {
        map.value = L.map(mapContainer.value).setView([51.505, -0.09], 13);
        mapLayers[selectedMapType.value].addTo(map.value);

        // Add click event
        map.value.on('click', onMapClick);

        console.log('Map initialized successfully');
        // Set location to current location on load

        moveToCurrentLocation();
    } catch (error) {
        console.error('Error initializing map:', error);
    }
};
// Set current view to specific location
const setMapView = (lat: number, lng: number, zoom: number = 13) => {
    if (map.value) {
        map.value.setView([lat, lng], zoom);
    }
};
// Calculate line length
const calculateLineLength = (points: ShapePoint[]): number => {
    if (points.length < 2) return 0;

    let totalLength = 0;
    for (let i = 1; i < points.length; i++) {
        totalLength += calculateDistance(points[i - 1], points[i]);
    }

    return totalLength / 1000; // Convert to kilometers
};
// Calculate distance between two points
const calculateDistance = (
    point1: { lat: number; lng: number },
    point2: { lat: number; lng: number },
): number => {
    const R = 6371e3; // Earth's radius in meters
    const φ1 = (point1.lat * Math.PI) / 180;
    const φ2 = (point2.lat * Math.PI) / 180;
    const Δφ = ((point2.lat - point1.lat) * Math.PI) / 180;
    const Δλ = ((point2.lng - point1.lng) * Math.PI) / 180;

    const a =
        Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c;
};
// Calculate polygon center
const getPolygonCenter = (
    points: PolygonPoint[],
): { lat: number; lng: number } => {
    const latSum = points.reduce((sum, point) => sum + point.lat, 0);
    const lngSum = points.reduce((sum, point) => sum + point.lng, 0);

    return {
        lat: latSum / points.length,
        lng: lngSum / points.length,
    };
};

// Visit polygon - center map on polygon
const visitPolygon = (polygon: PolygonData) => {
    if (!map.value) return;

    const center = getPolygonCenter(polygon.points);
    setMapView(center.lat, center.lng, 15);

    // Optionally fit bounds to show entire polygon
    // map.value.fitBounds(polygon.polygon.getBounds());
};
// Move to current location
const moveToCurrentLocation = () => {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }

    if (!map.value) {
        console.error('Map not initialized');
        return;
    }

    isLoadingLocation.value = true;

    navigator.geolocation.getCurrentPosition(
        (position) => {
            if (!map.value) {
                isLoadingLocation.value = false;
                return;
            }

            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            try {
                // Remove existing location marker and circle
                if (currentLocationMarker.value) {
                    map.value.removeLayer(currentLocationMarker.value);
                    currentLocationMarker.value = null;
                }
                if (currentLocationCircle.value) {
                    map.value.removeLayer(currentLocationCircle.value);
                    currentLocationCircle.value = null;
                }

                // Move map to current location
                map.value.setView([lat, lng], 15);

                isLoadingLocation.value = false;
            } catch (error) {
                console.error('Error adding location marker:', error);
                isLoadingLocation.value = false;
            }
        },
        (error) => {
            isLoadingLocation.value = false;
            let errorMessage = 'Unable to retrieve your location.';

            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = 'Location access denied by user.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = 'Location information is unavailable.';
                    break;
                case error.TIMEOUT:
                    errorMessage = 'Location request timed out.';
                    break;
            }

            notyf.error(errorMessage);
            console.error('Geolocation error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000, // 5 minutes
        },
    );
};

// Handle map clicks
const onMapClick = (e: L.LeafletMouseEvent) => {
    if (!map.value) return;

    if (isMarkerMode.value) {
        addMarker(e.latlng);
    } else if (isPolygonMode.value) {
        addShapePoint(e.latlng);
    }
};

// Add marker
const addMarker = (latlng: L.LatLng) => {
    if (!map.value) return;

    try {
        const marker = L.marker(latlng).addTo(map.value);

        marker.bindPopup(`
            <div>
                <strong>Marker ${markers.value.length + 1}</strong><br>
                Lat: ${latlng.lat.toFixed(6)}<br>
                Lng: ${latlng.lng.toFixed(6)}
                <br><button onclick="removeMarker(${markers.value.length})" style="margin-top: 5px; padding: 2px 6px; background: red; color: white; border: none; border-radius: 3px; cursor: pointer;">Remove</button>
            </div>
        `);

        markers.value.push({
            lat: latlng.lat,
            lng: latlng.lng,
            marker: marker,
        });
    } catch (error) {
        console.error('Error adding marker:', error);
    }
};

// Add polygon point
const addPolygonPoint = (latlng: L.LatLng) => {
    if (!map.value) return;

    try {
        const point: PolygonPoint = {
            lat: latlng.lat,
            lng: latlng.lng,
        };

        currentPolygonPoints.value.push(point);

        // Add temporary marker for this point
        const marker = L.marker(latlng, {
            icon: L.icon({
                iconUrl: markerIcon,
                iconRetinaUrl: markerRetina,
                shadowUrl: markerShadow,
                iconSize: [20, 32],
                iconAnchor: [10, 32],
                popupAnchor: [1, -30],
                shadowSize: [32, 32],
            }),
        }).addTo(map.value);

        marker.bindPopup(`
            <div>
                <strong>Polygon Point ${currentPolygonPoints.value.length}</strong><br>
                Lat: ${latlng.lat.toFixed(6)}<br>
                Lng: ${latlng.lng.toFixed(6)}
            </div>
        `);

        currentPolygonMarkers.value.push(marker);

        // Draw lines between points
        if (currentPolygonPoints.value.length > 1) {
            const previousPoint =
                currentPolygonPoints.value[
                    currentPolygonPoints.value.length - 2
                ];
            const line = L.polyline(
                [
                    [previousPoint.lat, previousPoint.lng],
                    [point.lat, point.lng],
                ],
                {
                    color: 'red',
                    weight: 2,
                    dashArray: '5, 5',
                },
            ).addTo(map.value);

            currentPolygonLines.value.push(line);
        }

        // If we have 3 or more points, show a preview line to the first point
        if (currentPolygonPoints.value.length >= 3) {
            // Remove any existing preview line
            const existingPreview = currentPolygonLines.value.find(
                (line) => (line as any).isPreview,
            );
            if (existingPreview) {
                map.value.removeLayer(existingPreview);
                const index =
                    currentPolygonLines.value.indexOf(existingPreview);
                currentPolygonLines.value.splice(index, 1);
            }

            // Add new preview line
            const firstPoint = currentPolygonPoints.value[0];
            const previewLine = L.polyline(
                [
                    [point.lat, point.lng],
                    [firstPoint.lat, firstPoint.lng],
                ],
                {
                    color: 'orange',
                    weight: 2,
                    dashArray: '10, 5',
                },
            ).addTo(map.value);

            (previewLine as any).isPreview = true;
            currentPolygonLines.value.push(previewLine);
        }
    } catch (error) {
        console.error('Error adding polygon point:', error);
    }
};

const addPointFromInput = () => {
    manualInputError.value = '';

    if (!isValidCoordinateInput.value) {
        manualInputError.value = 'Please enter valid coordinates.';
        return;
    }

    let lat: number, lng: number;

    if (coordinateFormat.value === 'DD') {
        lat = manualInput.value.lat!;
        lng = manualInput.value.lng!;
    } else if (coordinateFormat.value === 'DMS') {
        const {
            latDeg,
            latMin,
            latSec,
            latDir,
            lngDeg,
            lngMin,
            lngSec,
            lngDir,
        } = dmsInput.value;
        lat = dmsToDecimal(latDeg!, latMin!, latSec!, latDir);
        lng = dmsToDecimal(lngDeg!, lngMin!, lngSec!, lngDir);
    } else {
        // String input
        const parsed = parseCoordinateString(coordinateInput.value);
        if (!parsed) {
            manualInputError.value = 'Invalid coordinate format.';
            return;
        }
        lat = parsed.lat;
        lng = parsed.lng;
    }

    if (!isPolygonMode.value) {
        // Default to polygon mode when adding from input
        currentShapeType.value = 'polygon';
        isPolygonMode.value = true;
        isMarkerMode.value = false;
    }

    const latlng = L.latLng(lat, lng);
    addShapePoint(latlng);
    clearManualInput();
    map.value?.setView(latlng, map.value.getZoom());
};
const addShapePoint = (latlng: L.LatLng) => {
    if (!map.value) return;

    try {
        const point: ShapePoint = {
            lat: latlng.lat,
            lng: latlng.lng,
        };

        currentShapePoints.value.push(point);

        // Add temporary marker for this point
        const marker = L.marker(latlng, {
            icon: L.icon({
                iconUrl: markerIcon,
                iconRetinaUrl: markerRetina,
                shadowUrl: markerShadow,
                iconSize: [20, 32],
                iconAnchor: [10, 32],
                popupAnchor: [1, -30],
                shadowSize: [32, 32],
            }),
        }).addTo(map.value);

        marker.bindPopup(`
            <div>
                <strong>${currentShapeType.value === 'line' ? 'Line' : 'Polygon'} Point ${currentShapePoints.value.length}</strong><br>
                Lat: ${latlng.lat.toFixed(6)}<br>
                Lng: ${latlng.lng.toFixed(6)}
            </div>
        `);

        currentShapeMarkers.value.push(marker);

        // Draw lines between points
        if (currentShapePoints.value.length > 1) {
            const previousPoint =
                currentShapePoints.value[currentShapePoints.value.length - 2];
            const line = L.polyline(
                [
                    [previousPoint.lat, previousPoint.lng],
                    [point.lat, point.lng],
                ],
                {
                    color: 'red',
                    weight: 2,
                    dashArray: '5, 5',
                },
            ).addTo(map.value);

            currentShapeLines.value.push(line);
        }

        // For polygons, show preview line to first point when we have 3+ points
        if (
            currentShapeType.value === 'polygon' &&
            currentShapePoints.value.length >= 3
        ) {
            // Remove any existing preview line
            const existingPreview = currentShapeLines.value.find(
                (line) => (line as any).isPreview,
            );
            if (existingPreview) {
                map.value.removeLayer(existingPreview);
                const index = currentShapeLines.value.indexOf(existingPreview);
                currentShapeLines.value.splice(index, 1);
            }

            // Add new preview line
            const firstPoint = currentShapePoints.value[0];
            const previewLine = L.polyline(
                [
                    [point.lat, point.lng],
                    [firstPoint.lat, firstPoint.lng],
                ],
                {
                    color: 'orange',
                    weight: 2,
                    dashArray: '10, 5',
                },
            ).addTo(map.value);

            (previewLine as any).isPreview = true;
            currentShapeLines.value.push(previewLine);
        }
    } catch (error) {
        console.error('Error adding shape point:', error);
    }
};
// Finish polygon creation
const finishPolygon = () => {
    if (currentPolygonName.value == '') {
        notyf.error('Please provide a name for the polygon before finishing.');
        return;
    }
    if (currentPolygonPoints.value.length < 3 || !map.value) {
        return;
    }

    try {
        // Clear temporary markers and lines
        currentPolygonMarkers.value.forEach((marker) => {
            if (map.value) {
                map.value.removeLayer(marker);
            }
        });
        currentPolygonLines.value.forEach((line) => {
            if (map.value) {
                map.value.removeLayer(line);
            }
        });

        // Create the actual polygon
        const polygonCoords = currentPolygonPoints.value.map(
            (point) => [point.lat, point.lng] as [number, number],
        );
        const polygon = L.polygon(polygonCoords, {
            color: 'blue',
            weight: 2,
            fillOpacity: 0.2,
        }).addTo(map.value);

        // Calculate area
        const area = calculatePolygonArea(currentPolygonPoints.value);

        // Get polygon name or use default
        const polygonName =
            currentPolygonName.value.trim() ||
            `Polygon ${polygons.value.length + 1}`;

        // Add popup to polygon
        polygon.bindPopup(`
            <div>
                <strong>${polygonName}</strong><br>
                Points: ${currentPolygonPoints.value.length}<br>
                Area: ${area.toFixed(2)} km²<br>
                <button onclick="removePolygon(${polygons.value.length})" style="margin-top: 5px; padding: 2px 6px; background: red; color: white; border: none; border-radius: 3px; cursor: pointer;">Remove</button>
            </div>
        `);

        // Store the polygon
        polygons.value.push({
            name: currentPolygonName.value.trim() || undefined,
            points: [...currentPolygonPoints.value],
            polygon: polygon,
            area: area,
        });

        // Fit map to show the polygon
        map.value.fitBounds(polygon.getBounds());

        // Reset polygon creation state
        cancelPolygon();
    } catch (error) {
        console.error('Error finishing polygon:', error);
    }
};

// Cancel polygon creation
const cancelPolygon = () => {
    if (!map.value) return;

    try {
        // Clear temporary markers and lines
        currentPolygonMarkers.value.forEach((marker) => {
            map.value!.removeLayer(marker);
        });
        currentPolygonLines.value.forEach((line) => {
            map.value!.removeLayer(line);
        });

        currentPolygonPoints.value = [];
        currentPolygonMarkers.value = [];
        currentPolygonLines.value = [];
        currentPolygonName.value = '';
        isPolygonMode.value = false;
    } catch (error) {
        console.error('Error canceling polygon:', error);
    }
};

// Remove last point
const removeLastPoint = () => {
    if (currentShapePoints.value.length === 0 || !map.value) return;

    try {
        // Remove last point
        currentShapePoints.value.pop();

        // Remove last marker
        const lastMarker = currentShapeMarkers.value.pop();
        if (lastMarker) {
            map.value.removeLayer(lastMarker);
        }

        // Remove connecting lines and rebuild
        currentShapeLines.value.forEach((line) => {
            if (map.value) {
                map.value.removeLayer(line);
            }
        });
        currentShapeLines.value = [];

        // Rebuild lines
        for (let i = 1; i < currentShapePoints.value.length; i++) {
            const prevPoint = currentShapePoints.value[i - 1];
            const currPoint = currentShapePoints.value[i];
            const line = L.polyline(
                [
                    [prevPoint.lat, prevPoint.lng],
                    [currPoint.lat, currPoint.lng],
                ],
                {
                    color: 'red',
                    weight: 2,
                    dashArray: '5, 5',
                },
            ).addTo(map.value);
            currentShapeLines.value.push(line);
        }

        // Add preview line only for polygons with 3+ points
        if (
            currentShapeType.value === 'polygon' &&
            currentShapePoints.value.length >= 3
        ) {
            const firstPoint = currentShapePoints.value[0];
            const lastPoint =
                currentShapePoints.value[currentShapePoints.value.length - 1];
            const previewLine = L.polyline(
                [
                    [lastPoint.lat, lastPoint.lng],
                    [firstPoint.lat, firstPoint.lng],
                ],
                {
                    color: 'orange',
                    weight: 2,
                    dashArray: '10, 5',
                },
            ).addTo(map.value);
            (previewLine as any).isPreview = true;
            currentShapeLines.value.push(previewLine);
        }
    } catch (error) {
        console.error('Error removing last point:', error);
    }
};

// Calculate polygon area using shoelace formula
const calculatePolygonArea = (points: PolygonPoint[]): number => {
    if (points.length < 3) return 0;

    const R = 6371; // Earth's radius in km

    // Convert to radians and calculate area using spherical excess formula
    let area = 0;
    const n = points.length;

    for (let i = 0; i < n; i++) {
        const j = (i + 1) % n;
        const lat1 = (points[i].lat * Math.PI) / 180;
        const lng1 = (points[i].lng * Math.PI) / 180;
        const lat2 = (points[j].lat * Math.PI) / 180;
        const lng2 = (points[j].lng * Math.PI) / 180;

        area += (lng2 - lng1) * (2 + Math.sin(lat1) + Math.sin(lat2));
    }

    area = Math.abs((area * R * R) / 2);
    return area;
};
// DMS Conversion
const dmsToDecimal = (
    degrees: number,
    minutes: number,
    seconds: number,
    direction: string,
): number => {
    let decimal = degrees + minutes / 60 + seconds / 3600;
    if (direction === 'S' || direction === 'W') {
        decimal = -decimal;
    }
    return decimal;
};
const parseCoordinateString = (
    input: string,
): { lat: number; lng: number } | null => {
    const trimmed = input.trim();

    // Try DD format first (decimal degrees)
    const ddMatch = trimmed.match(/^(-?\d+\.?\d*)\s*,\s*(-?\d+\.?\d*)$/);
    if (ddMatch) {
        const lat = parseFloat(ddMatch[1]);
        const lng = parseFloat(ddMatch[2]);
        if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            return { lat, lng };
        }
    }

    // Try decimal degrees with direction (like 1.840707°S, 110.002740°E)
    const ddWithDirectionMatch = trimmed.match(
        /^(\d+\.?\d*)°([NSEWnsew])\s*,\s*(\d+\.?\d*)°([NSEWnsew])$/,
    );
    if (ddWithDirectionMatch) {
        const [, latValue, latDir, lngValue, lngDir] = ddWithDirectionMatch;
        let lat = parseFloat(latValue);
        let lng = parseFloat(lngValue);

        // Apply direction
        if (latDir === 'S') lat = -lat;
        if (lngDir === 'W') lng = -lng;

        if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            return { lat, lng };
        }
    }

    // Try DMS format (1°50'39.0"S 110°00'18.3"E)
    const dmsPattern =
        /(\d+)°(\d+)'([\d.]+)"([NSEWnsew])\s*,?\s*(\d+)°(\d+)'([\d.]+)"([NSEWnsew])/;
    const dmsMatch = trimmed.match(dmsPattern);

    if (dmsMatch) {
        const [
            ,
            latDeg,
            latMin,
            latSec,
            latDir,
            lngDeg,
            lngMin,
            lngSec,
            lngDir,
        ] = dmsMatch;

        const lat = dmsToDecimal(
            parseInt(latDeg),
            parseInt(latMin),
            parseFloat(latSec),
            latDir,
        );
        const lng = dmsToDecimal(
            parseInt(lngDeg),
            parseInt(lngMin),
            parseFloat(lngSec),
            lngDir,
        );

        if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            return { lat, lng };
        }
    }

    // Try additional formats without commas (space separated)
    const spaceFormats = [
        // Format: 1.840707°S 110.002740°E (space separated)
        /^(\d+\.?\d*)°([NSEWnsew])\s+(\d+\.?\d*)°([NSEWnsew])$/,
        // Format: 1°50'39.0"S 110°00'18.3"E (space separated DMS)
        /^(\d+)°(\d+)'([\d.]+)"([NSEWnsew])\s+(\d+)°(\d+)'([\d.]+)"([NSEWnsew])$/,
    ];

    for (const pattern of spaceFormats) {
        const match = trimmed.match(pattern);
        if (match) {
            if (match.length === 5) {
                // Decimal degrees with direction
                const [, latValue, latDir, lngValue, lngDir] = match;
                let lat = parseFloat(latValue);
                let lng = parseFloat(lngValue);

                if (latDir === 'S') lat = -lat;
                if (lngDir === 'W') lng = -lng;

                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { lat, lng };
                }
            } else if (match.length === 9) {
                // DMS format
                const [
                    ,
                    latDeg,
                    latMin,
                    latSec,
                    latDir,
                    lngDeg,
                    lngMin,
                    lngSec,
                    lngDir,
                ] = match;

                const lat = dmsToDecimal(
                    parseInt(latDeg),
                    parseInt(latMin),
                    parseFloat(latSec),
                    latDir,
                );
                const lng = dmsToDecimal(
                    parseInt(lngDeg),
                    parseInt(lngMin),
                    parseFloat(lngSec),
                    lngDir,
                );

                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { lat, lng };
                }
            }
        }
    }

    return null;
};

// Clear manual input
const clearManualInput = () => {
    manualInput.value = {
        lat: null,
        lng: null,
    };
    dmsInput.value = {
        latDeg: null,
        latMin: null,
        latSec: null,
        latDir: 'N',
        lngDeg: null,
        lngMin: null,
        lngSec: null,
        lngDir: 'E',
    };
    coordinateInput.value = '';
    manualInputError.value = '';
};

// Toggle modes
const toggleMarkerMode = () => {
    isMarkerMode.value = !isMarkerMode.value;
    if (isMarkerMode.value) {
        cancelPolygon();
    }
};
const toggleDrawingMode = (type: 'line' | 'polygon') => {
    if (isPolygonMode.value && currentShapeType.value === type) {
        // Cancel current drawing
        cancelShape();
    } else {
        // Start new drawing
        cancelShape(); // Clear any existing drawing
        currentShapeType.value = type;
        isPolygonMode.value = true;
        isMarkerMode.value = false;
    }
};
const toggleCoordinateInput = () => {
    showCoordinateInput.value = !showCoordinateInput.value;
    if (!showCoordinateInput.value) {
        // When hiding coordinate input, clear any input and errors
        clearManualInput();
    } else {
        hideCoordinateInput.value = false;
        hideShapeCreation.value = true;
    }
};

const togglePolygonMode = () => {
    isPolygonMode.value = !isPolygonMode.value;
    if (isPolygonMode.value) {
        isMarkerMode.value = false;
    } else {
        cancelPolygon();
    }
};

// Change map type
const changeMapType = () => {
    if (!map.value) return;

    try {
        // Remove current layer
        map.value.eachLayer((layer) => {
            if (layer instanceof L.TileLayer) {
                map.value!.removeLayer(layer);
            }
        });

        // Add new layer
        mapLayers[selectedMapType.value].addTo(map.value);
    } catch (error) {
        console.error('Error changing map type:', error);
    }
};
// Remove shape
const removeShape = (index: number) => {
    if (shapes.value[index] && map.value) {
        map.value.removeLayer(shapes.value[index].shape);
        shapes.value.splice(index, 1);
    }
};

// Visit shape - center map on shape
const visitShape = (shape: ShapeData) => {
    if (!map.value) return;

    if (shape.type === 'polygon') {
        map.value.fitBounds((shape.shape as L.Polygon).getBounds());
    } else {
        map.value.fitBounds((shape.shape as L.Polyline).getBounds());
    }
};

// Open save modal
const openSaveModal = () => {
    if (shapes.value.length === 0) {
        notyf.error('No shapes to save.');
        return;
    }
    showSaveModal.value = true;
    collectionName.value = '';
};
// Finish shape creation
const finishShape = () => {
    if (currentShapeName.value === '') {
        notyf.error(
            `Please provide a name for the ${currentShapeType.value} before finishing.`,
        );
        return;
    }

    if (
        currentShapePoints.value.length < minPointsRequired.value ||
        !map.value
    ) {
        return;
    }

    try {
        // Clear temporary markers and lines
        currentShapeMarkers.value.forEach((marker) => {
            if (map.value) {
                map.value.removeLayer(marker);
            }
        });
        currentShapeLines.value.forEach((line) => {
            if (map.value) {
                map.value.removeLayer(line);
            }
        });

        // Create the actual shape
        const shapeCoords = currentShapePoints.value.map(
            (point) => [point.lat, point.lng] as [number, number],
        );

        let shape: L.Polygon | L.Polyline;
        let area: number | undefined;
        let length: number | undefined;

        if (currentShapeType.value === 'polygon') {
            shape = L.polygon(shapeCoords, {
                color: 'blue',
                weight: 2,
                fillOpacity: 0.2,
            }).addTo(map.value);
            area = calculatePolygonArea(currentShapePoints.value);
        } else {
            shape = L.polyline(shapeCoords, {
                color: 'green',
                weight: 3,
                opacity: 0.8,
            }).addTo(map.value);
            length = calculateLineLength(currentShapePoints.value);
        }

        // Get shape name
        const shapeName =
            currentShapeName.value.trim() ||
            `${currentShapeType.value === 'line' ? 'Line' : 'Polygon'} ${shapes.value.length + 1}`;

        // Add popup to shape
        const popupContent =
            currentShapeType.value === 'polygon'
                ? `<div><strong>${shapeName}</strong><br>Points: ${currentShapePoints.value.length}<br>Area: ${area?.toFixed(2)} km²</div>`
                : `<div><strong>${shapeName}</strong><br>Points: ${currentShapePoints.value.length}<br>Length: ${length?.toFixed(2)} km</div>`;

        shape.bindPopup(popupContent);

        // Store the shape
        shapes.value.push({
            id: Date.now().toString(),
            name: shapeName,
            type: currentShapeType.value,
            points: [...currentShapePoints.value],
            shape: shape,
            area: area,
            length: length,
        });

        // Fit map to show the shape
        if (currentShapeType.value === 'polygon') {
            map.value.fitBounds((shape as L.Polygon).getBounds());
        } else {
            map.value.fitBounds((shape as L.Polyline).getBounds());
        }

        // Reset shape creation state
        cancelShape();
    } catch (error) {
        console.error('Error finishing shape:', error);
    }
};
const cancelShape = () => {
    if (!map.value) return;

    try {
        // Clear temporary markers and lines
        currentShapeMarkers.value.forEach((marker) => {
            map.value!.removeLayer(marker);
        });
        currentShapeLines.value.forEach((line) => {
            map.value!.removeLayer(line);
        });

        currentShapePoints.value = [];
        currentShapeMarkers.value = [];
        currentShapeLines.value = [];
        currentShapeName.value = '';
        isPolygonMode.value = false;
    } catch (error) {
        console.error('Error canceling shape:', error);
    }
};
// Save Shapes
const saveShapes = async () => {
    if (shapes.value.length === 0) {
        notyf.error('No shapes to save.');
        return;
    }

    if (collectionName.value.trim() === '') {
        notyf.error('Please enter a collection name.');
        return;
    }

    isSaving.value = true;

    try {
        const saveData = {
            name: collectionName.value.trim(),
            shapes: shapes.value.map((shape) => ({
                name: shape.name,
                type: shape.type,
                points: shape.points.map((point) => ({
                    lat: point.lat,
                    lng: point.lng,
                })),
                coordinates: shape.points.map((point) => [
                    point.lng,
                    point.lat,
                ]),
                ...(shape.type === 'polygon' && { area_km2: shape.area }),
                ...(shape.type === 'line' && { length_km: shape.length }),
                bounds: {
                    north: Math.max(...shape.points.map((p) => p.lat)),
                    south: Math.min(...shape.points.map((p) => p.lat)),
                    east: Math.max(...shape.points.map((p) => p.lng)),
                    west: Math.min(...shape.points.map((p) => p.lng)),
                },
            })),
            metadata: {
                totalShapes: shapes.value.length,
                totalPoints: shapes.value.reduce(
                    (sum, s) => sum + s.points.length,
                    0,
                ),
                shapeTypes: [...new Set(shapes.value.map((s) => s.type))],
                mapType: selectedMapType.value,
                createdAt: new Date().toISOString(),
                mapCenter: map.value?.getCenter(),
                mapZoom: map.value?.getZoom(),
            },
        };

        const response = await axios.post('/create-marker/save', saveData);

        console.log('Save response:', response.data);
        notyf.success(
            `Collection "${collectionName.value}" saved successfully!`,
        );

        // Close modal and optionally clear shapes
        showSaveModal.value = false;
        clearAll();
    } catch (error) {
        console.error('Error saving shapes:', error);

        if (error.response) {
            console.error('Server error:', error.response.data);
            notyf.error(
                `Failed to save collection: ${error.response.data.message || error.response.data.error || 'Server error'}`,
            );
        } else if (error.request) {
            console.error('Network error:', error.request);
            notyf.error(
                'Network error: Unable to reach the server. Please check your internet connection.',
            );
        } else {
            console.error('Error:', error.message);
            notyf.error(`Error: ${error.message}`);
        }
    } finally {
        isSaving.value = false;
    }
};

// Clear all
const clearAll = () => {
    if (!map.value) return;

    try {
        // Clear markers
        markers.value.forEach((item) => {
            map.value!.removeLayer(item.marker);
        });
        markers.value = [];

        // Clear shapes
        shapes.value.forEach((item) => {
            map.value!.removeLayer(item.shape);
        });
        shapes.value = [];

        // Clear current location marker and circle
        if (currentLocationMarker.value) {
            map.value.removeLayer(currentLocationMarker.value);
            currentLocationMarker.value = null;
        }
        if (currentLocationCircle.value) {
            map.value.removeLayer(currentLocationCircle.value);
            currentLocationCircle.value = null;
        }

        // Cancel any ongoing shape creation
        cancelShape();
        showCoordinateInput.value = false;
        clearManualInput();
    } catch (error) {
        console.error('Error clearing all:', error);
    }
};

// Log data for API
const logDataForAPI = () => {
    const apiData = {
        mapType: selectedMapType.value,
        markers: markers.value.map((marker, index) => ({
            id: index + 1,
            latitude: marker.lat,
            longitude: marker.lng,
            coordinates: [marker.lng, marker.lat], // GeoJSON format [lng, lat]
        })),
        polygons: polygons.value.map((polygon, index) => ({
            id: index + 1,
            name: polygon.name || `Polygon ${index + 1}`,
            points: polygon.points.length,
            coordinates: polygon.points.map((point) => [point.lng, point.lat]), // GeoJSON format
            area_km2: polygon.area,
            bounds: {
                north: Math.max(...polygon.points.map((p) => p.lat)),
                south: Math.min(...polygon.points.map((p) => p.lat)),
                east: Math.max(...polygon.points.map((p) => p.lng)),
                west: Math.min(...polygon.points.map((p) => p.lng)),
            },
            vertices: polygon.points.map((point, pointIndex) => ({
                index: pointIndex + 1,
                latitude: point.lat,
                longitude: point.lng,
            })),
        })),
        metadata: {
            totalMarkers: markers.value.length,
            totalPolygons: polygons.value.length,
            totalPolygonPoints: polygons.value.reduce(
                (sum, p) => sum + p.points.length,
                0,
            ),
            createdAt: new Date().toISOString(),
            mapCenter: map.value?.getCenter(),
            mapZoom: map.value?.getZoom(),
        },
    };

    console.log('=== API Data to Send ===');
    console.log(JSON.stringify(apiData, null, 2));
    console.log('========================');

    return apiData;
};
const removePolygon = (index: number) => {
    if (polygons.value[index] && map.value) {
        map.value.removeLayer(polygons.value[index].polygon);
        polygons.value.splice(index, 1);
    }
};
// Global functions for popup buttons
(window as any).removeMarker = (index: number) => {
    if (markers.value[index] && map.value) {
        map.value.removeLayer(markers.value[index].marker);
        markers.value.splice(index, 1);
    }
};

(window as any).removePolygon = (index: number) => {
    removePolygon(index);
};
const addSymbolToInput = (symbol: string) => {
    insertAtCursor(
        focusedInput.value as HTMLInputElement | HTMLTextAreaElement,
        symbol,
    );
};
const insertAtCursor = (
    element: HTMLInputElement | HTMLTextAreaElement,
    text: string,
) => {
    if (!element) return;

    const start = element.selectionStart || 0;
    const end = element.selectionEnd || 0;
    const currentValue = element.value || '';

    // Insert text at cursor position
    const newValue =
        currentValue.slice(0, start) + text + currentValue.slice(end);

    element.value = newValue;

    // Update the cursor position
    const newPosition = start + text.length;
    if (coordinateFormat === 'STRING') {
        element.setSelectionRange(newPosition, newPosition);
    }

    // Trigger input event to update v-model
    element.dispatchEvent(new Event('input', { bubbles: true }));
};
const focusedInput = ref<HTMLElement | null>(null);
// Lifecycle
onMounted(() => {
    initMap();

    document
        .querySelectorAll(
            '#manual-coordinate-form input, #manual-coordinate-form textarea',
        )
        .forEach((element) => {
            element.addEventListener('focusin', () => {
                focusedInput.value = element as HTMLElement;
            });
        });
});

onUnmounted(() => {
    if (map.value) {
        try {
            map.value.remove();
        } catch (error) {
            console.error('Error removing map:', error);
        }
        map.value = null;
    }
});
</script>
