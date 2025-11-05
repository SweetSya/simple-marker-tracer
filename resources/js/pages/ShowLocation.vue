<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="border-b bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Location Tracker
                    </h1>
                    <div v-if="location" class="text-sm text-gray-600">
                        {{ location.name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div v-if="error" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
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
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <div class="mt-2 text-sm text-red-700">{{ error }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div
            v-else-if="location"
            class="relative mx-auto w-full max-w-7xl px-4 py-4 sm:px-6 md:py-8 lg:px-8"
        >
            <!-- Status Panel -->
            <div class="absolute bottom-16 left-0 z-[1000] w-full px-13">
                <div
                    class="rounded-lg border-2 border-white bg-white/80 p-6 shadow-lg backdrop-blur-sm"
                    :class="
                        isTrackingLocation && isInsideOrNearAnyShape
                            ? '!border-green-500'
                            : isTrackingLocation && !isInsideOrNearAnyShape
                              ? '!border-red-500'
                              : ''
                    "
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-2"
                    >
                        <div>
                            <div class="flex gap-4">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Location Status
                                </h2>
                                <div class="flex items-center">
                                    <div
                                        :class="[
                                            'mr-2 h-3 w-3 rounded-full',
                                            isInsideOrNearAnyShape
                                                ? 'bg-green-400'
                                                : 'bg-red-400',
                                        ]"
                                    ></div>
                                    <span
                                        :class="[
                                            'text-sm font-medium',
                                            isInsideOrNearAnyShape
                                                ? 'text-green-800'
                                                : 'text-red-800',
                                        ]"
                                    >
                                        {{
                                            isInsideOrNearAnyShape
                                                ? `Inside/Near: ${insideShapeNames.join(', ')}`
                                                : 'Outside All Areas'
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center space-x-4">
                                <div class="text-xs text-gray-500 sm:text-sm">
                                    Last updated: {{ lastUpdated }}
                                </div>
                                <div
                                    v-if="distanceToNearestShape !== null"
                                    class="text-xs text-gray-500 sm:text-sm"
                                >
                                    Nearest:
                                    {{ distanceToNearestShape.toFixed(0) }}m to
                                    {{ nearestShapeName }}
                                </div>
                            </div>
                        </div>
                        <button
                            @click="toggleLocationTracking"
                            :disabled="isGettingLocation"
                            :class="[
                                'w-full rounded-md px-4 py-2 text-sm font-medium transition-colors',
                                isTrackingLocation
                                    ? 'bg-red-600 text-white hover:bg-red-700'
                                    : 'bg-blue-600 text-white hover:bg-blue-700',
                                isGettingLocation && !isTrackingLocation
                                    ? 'cursor-not-allowed opacity-50'
                                    : '',
                            ]"
                        >
                            {{
                                isGettingLocation
                                    ? 'Getting Location...'
                                    : isTrackingLocation
                                      ? 'Stop Tracking'
                                      : 'Start Tracking'
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Shape Info Panel -->
            <div v-if="location.data.shapes" class="mb-5 w-full">
                <div
                    class="rounded-lg border border-gray-200 bg-white/95 p-3 shadow-lg backdrop-blur-sm"
                >
                    <h3 class="mb-2 text-sm font-semibold text-gray-900">
                        Collection: {{ location.name }}
                    </h3>
                    <div
                        class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3"
                    >
                        <div
                            v-for="(shape, index) in location.data.shapes"
                            :key="index"
                            :class="[
                                'flex items-center rounded-md p-2 text-xs transition-all duration-200',
                                shape.type === 'polygon'
                                    ? 'bg-blue-50 text-blue-800'
                                    : 'bg-green-50 text-green-800',
                                isInsideShape(shape) ||
                                (shape.type === 'line' &&
                                    currentLocation &&
                                    distanceToLine(
                                        currentLocation,
                                        shape.points,
                                    ) <= 5)
                                    ? 'bg-green-100 ring-2 ring-green-400'
                                    : '',
                            ]"
                        >
                            <svg
                                class="mr-2 h-3 w-3 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    v-if="shape.type === 'polygon'"
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
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-medium">
                                    {{ shape.name }}
                                </div>
                                <div class="text-xs opacity-75">
                                    {{
                                        shape.type === 'polygon'
                                            ? `${shape.area_km2?.toFixed(2)} km²`
                                            : `${shape.length_km?.toFixed(2)} km`
                                    }}
                                </div>
                            </div>
                            <!-- Navigation Button -->
                            <button
                                @click="navigateToShape(shape)"
                                class="ml-2 cursor-pointer rounded-md border-2 bg-white/80 p-1 text-gray-600 shadow-xs transition transition-colors duration-200 hover:scale-110 hover:bg-white hover:text-gray-800 hover:opacity-65 hover:shadow-md"
                                title="Navigate to this shape"
                            >
                                <svg
                                    class="h-3 w-3"
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
                            </button>
                            <!-- Status indicator -->
                            <div
                                v-if="
                                    isInsideShape(shape) ||
                                    (shape.type === 'line' &&
                                        currentLocation &&
                                        distanceToLine(
                                            currentLocation,
                                            shape.points,
                                        ) <= 5)
                                "
                                class="ml-1 h-2 w-2 rounded-full bg-green-400"
                                :title="
                                    shape.type === 'polygon'
                                        ? 'You are inside this shape'
                                        : 'You are near this line'
                                "
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="relative rounded-lg bg-white shadow">
                <!-- Map Controls -->
                <div
                    class="absolute top-4 right-4 z-[1000] flex flex-col space-y-2"
                >
                    <!-- Follow User Button -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white/95 p-1 shadow-lg backdrop-blur-sm"
                    >
                        <button
                            @click="followUser"
                            :disabled="!currentLocation"
                            :class="[
                                'flex w-full items-center justify-center rounded-md px-1 py-1 text-xs font-medium shadow-sm transition-all duration-200',
                                currentCameraMode === 'follow'
                                    ? 'bg-blue-500 text-white hover:bg-blue-600'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                !currentLocation
                                    ? 'cursor-not-allowed opacity-50'
                                    : '',
                            ]"
                        >
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
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            {{
                                currentCameraMode === 'follow'
                                    ? 'Following'
                                    : 'Follow Me'
                            }}
                        </button>
                    </div>

                    <!-- Overview Button -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white/95 p-1 shadow-lg backdrop-blur-sm"
                    >
                        <button
                            @click="showOverview"
                            :disabled="!currentLocation"
                            :class="[
                                'flex w-full items-center justify-center rounded-md px-1 py-1 text-xs font-medium shadow-sm transition-all duration-200',
                                currentCameraMode === 'overview'
                                    ? 'bg-green-500 text-white hover:bg-green-600'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                !currentLocation
                                    ? 'cursor-not-allowed opacity-50'
                                    : '',
                            ]"
                        >
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
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                            {{
                                currentCameraMode === 'overview'
                                    ? 'Center Active'
                                    : 'Center'
                            }}
                        </button>
                    </div>

                    <!-- Line Toggle -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white/95 p-1 shadow-lg backdrop-blur-sm"
                    >
                        <button
                            @click="toggleConnectionLine"
                            :class="[
                                'flex w-full items-center justify-center rounded-md px-1 py-1 text-xs font-medium transition-all duration-200',
                                showConnectionLine
                                    ? 'bg-orange-500 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                !currentLocation
                                    ? 'cursor-not-allowed opacity-50'
                                    : '',
                            ]"
                            :disabled="!currentLocation"
                        >
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
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                            {{
                                showConnectionLine ? 'Hide Lines' : 'Show Lines'
                            }}
                        </button>
                    </div>
                    <!-- Free Roam -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white/95 p-1 shadow-lg backdrop-blur-sm"
                    >
                        <button
                            @click="toggleFreeRoam"
                            :class="[
                                'flex w-full items-center justify-center rounded-md px-1 py-1 text-xs font-medium transition-all duration-200',
                                currentCameraMode === 'free-roam'
                                    ? 'bg-amber-500 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                !currentLocation
                                    ? 'cursor-not-allowed opacity-50'
                                    : '',
                            ]"
                            :disabled="!currentLocation"
                        >
                            <Hand class="mr-1 h-4 w-4" />
                            {{
                                currentCameraMode === 'free-roam'
                                    ? 'Free View'
                                    : 'Locked View'
                            }}
                        </button>
                    </div>
                    <!-- Tile Selector -->
                    <div
                        class="rounded-lg border border-gray-200 bg-white/95 p-1 shadow-lg backdrop-blur-sm"
                    >
                        <div class="relative">
                            <button
                                @click="toggleTileSelector"
                                :class="[
                                    'flex w-full items-center justify-center rounded-md px-1 py-1 text-xs font-medium transition-all duration-200',
                                    showTileSelector
                                        ? 'bg-purple-500 text-white shadow-sm'
                                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                                ]"
                            >
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
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                    />
                                </svg>
                                {{ currentTileLayer.name }}
                            </button>

                            <!-- Tile Selector Dropdown -->
                            <div
                                v-if="showTileSelector"
                                class="absolute top-full right-0 z-[1001] mt-1 w-48 rounded-lg border border-gray-200 bg-white shadow-lg"
                            >
                                <div class="p-2">
                                    <div
                                        class="mb-2 px-2 text-xs font-medium text-gray-500"
                                    >
                                        Map Style
                                    </div>
                                    <button
                                        v-for="tile in availableTiles"
                                        :key="tile.id"
                                        @click="selectTile(tile)"
                                        :class="[
                                            'flex w-full items-center rounded-md px-1 py-1 text-xs transition-colors',
                                            currentTileLayer.id === tile.id
                                                ? 'bg-purple-100 text-purple-800'
                                                : 'text-gray-700 hover:bg-gray-100',
                                        ]"
                                    >
                                        <div
                                            :class="[
                                                'mr-2 h-3 w-3 rounded-full border-2',
                                                currentTileLayer.id === tile.id
                                                    ? 'border-purple-500 bg-purple-500'
                                                    : 'border-gray-300',
                                            ]"
                                        ></div>
                                        <div>
                                            <div class="font-medium">
                                                {{ tile.name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ tile.description }}
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    ref="mapContainer"
                    class="h-screen max-h-[800px] min-h-[600px] w-full rounded-lg"
                ></div>
                <div
                    :class="[
                        !isTrackingLocation ? 'bg-black/40' : 'bg-transparent',
                        isFreeRoamActive ? 'hidden' : 'block',
                    ]"
                    class="absolute top-0 left-0 z-[500] flex h-full w-full items-center justify-center rounded-lg"
                >
                    <p
                        v-show="!isTrackingLocation"
                        class="text-center text-white"
                    >
                        Start <b>tracking</b> to reveal your location
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { computed, onMounted, onUnmounted, ref } from 'vue';

// Fix leaflet markers
import markerRetina from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import { Hand } from 'lucide-vue-next';

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

// Updated Props interface
const props = defineProps<{
    error: string | null;
    location: {
        id: string;
        name: string;
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
        share_code: string;
    } | null;
}>();

// Tile layer definitions
const availableTiles = [
    {
        id: 'osm',
        name: 'OpenStreetMap',
        description: 'Standard map view',
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '© OpenStreetMap contributors',
    },
    {
        id: 'satellite',
        name: 'Satellite',
        description: 'Aerial imagery',
        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        attribution: '© Esri, Maxar, Earthstar Geographics',
    },
    {
        id: 'terrain',
        name: 'Terrain',
        description: 'Topographic view',
        url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
        attribution: '© OpenTopoMap contributors',
    },
    {
        id: 'dark',
        name: 'Dark Mode',
        description: 'Dark themed map',
        url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
        attribution: '© CARTO',
    },
];

// Reactive variables
const mapContainer = ref<HTMLElement>();
const map = ref<L.Map | null>(null);
const currentTileLayerRef = ref<L.TileLayer | null>(null);
const shapeElements = ref<Array<{ shape: L.Polygon | L.Polyline; data: any }>>(
    [],
);
const shapeCenters = ref<Array<{ lat: number; lng: number; name: string }>>([]);
const shapeCenterMarkers = ref<L.Marker[]>([]);
const currentLocationMarker = ref<L.Marker | null>(null);
const currentLocationCircle = ref<L.Circle | null>(null);
const connectionLines = ref<L.Polyline[]>([]);
const currentLocation = ref<{
    lat: number;
    lng: number;
    accuracy?: number;
} | null>(null);
const isTrackingLocation = ref(false);
const isGettingLocation = ref(false);
const lastUpdated = ref('Never');
const trackingInterval = ref<number | null>(null);
const showConnectionLine = ref(false);
const showTileSelector = ref(false);
const currentTileLayer = ref(availableTiles[0]);

// Add camera mode states
const isOverviewActive = ref(false);
const isFollowingUser = ref(false);
const isFreeRoamActive = ref(false);
const currentCameraMode = ref<'overview' | 'follow' | 'free-roam' | ''>('');

const movementStatus = computed(() => {
    if (!locationState.value.lastMovementTime) return 'No movement detected';

    if (locationState.value.isMoving) {
        const speed = (locationState.value.movementSpeed * 3.6).toFixed(1); // Convert to km/h
        return `Moving at ${speed} km/h`;
    }

    const timeSinceMovement = Math.round(
        (new Date().getTime() -
            locationState.value.lastMovementTime.getTime()) /
            1000,
    );

    if (timeSinceMovement < 60) {
        return `Stopped ${timeSinceMovement}s ago`;
    } else {
        return `Stopped ${Math.round(timeSinceMovement / 60)}m ago`;
    }
});
// function to calculate distance from point to line
const distanceToLine = (
    point: { lat: number; lng: number },
    linePoints: Array<{ lat: number; lng: number }>,
): number => {
    if (linePoints.length < 2) return Infinity;

    let minDistance = Infinity;

    // Check distance to each line segment
    for (let i = 0; i < linePoints.length - 1; i++) {
        const segmentStart = linePoints[i];
        const segmentEnd = linePoints[i + 1];
        const distance = distanceToLineSegment(point, segmentStart, segmentEnd);
        minDistance = Math.min(minDistance, distance);
    }

    return minDistance;
};
// Enhanced tracking configuration for mobile precision
const trackingConfig = {
    // High precision settings for step-by-step tracking
    enableHighAccuracy: true,
    timeout: 15000, // Increased timeout for better accuracy
    maximumAge: 0, // Always get fresh location

    // Tracking intervals (milliseconds)
    highPrecisionInterval: 2000, // 2 seconds for active following

    // Movement detection
    minimumMovementDistance: 0.5, // 0.5 meters minimum movement to update
    significantMovementDistance: 2, // 2 meters for significant movement

    // Zoom levels for different modes
    walkingZoom: 21, // Maximum zoom for walking precision
    followingZoom: 20, // High zoom for following mode
    overviewZoom: 16, // Lower zoom for overview
};

// Enhanced location state
const locationState = ref({
    isMoving: false,
    lastMovementTime: null as Date | null,
    movementSpeed: 0, // meters per second
    heading: null as number | null,
    lastPosition: null as {
        lat: number;
        lng: number;
        timestamp: number;
    } | null,
    accuracy: null as number | null,
    isHighAccuracy: false,
});
// Movement detection
const detectMovement = (newLocation: {
    lat: number;
    lng: number;
    accuracy?: number;
}) => {
    const now = new Date();

    if (locationState.value.lastPosition) {
        const distance = calculateDistance(
            locationState.value.lastPosition,
            newLocation,
        );

        const timeDiff =
            (now.getTime() - locationState.value.lastPosition.timestamp) / 1000; // seconds
        const speed = distance / timeDiff; // meters per second

        locationState.value.movementSpeed = speed;

        // Consider moving if distance > minimum threshold or speed indicates movement
        locationState.value.isMoving =
            distance > trackingConfig.minimumMovementDistance || speed > 0.1; // 0.1 m/s = very slow walking

        if (locationState.value.isMoving) {
            locationState.value.lastMovementTime = now;
        }

        // Calculate heading if moving significantly
        if (distance > trackingConfig.significantMovementDistance) {
            locationState.value.heading = calculateBearing(
                locationState.value.lastPosition,
                newLocation,
            );
        }
    }

    // Update last position
    locationState.value.lastPosition = {
        lat: newLocation.lat,
        lng: newLocation.lng,
        timestamp: now.getTime(),
    };

    locationState.value.accuracy = newLocation.accuracy || null;
    locationState.value.isHighAccuracy = (newLocation.accuracy || 100) < 10; // Consider high accuracy if < 10m
};

// Calculate bearing between two points
const calculateBearing = (
    point1: { lat: number; lng: number },
    point2: { lat: number; lng: number },
): number => {
    const lat1 = (point1.lat * Math.PI) / 180;
    const lat2 = (point2.lat * Math.PI) / 180;
    const deltaLng = ((point2.lng - point1.lng) * Math.PI) / 180;

    const x = Math.sin(deltaLng) * Math.cos(lat2);
    const y =
        Math.cos(lat1) * Math.sin(lat2) -
        Math.sin(lat1) * Math.cos(lat2) * Math.cos(deltaLng);

    const bearing = Math.atan2(x, y);
    return ((bearing * 180) / Math.PI + 360) % 360; // Convert to degrees and normalize
};
// Calculate distance from point to line segment
const distanceToLineSegment = (
    point: { lat: number; lng: number },
    lineStart: { lat: number; lng: number },
    lineEnd: { lat: number; lng: number },
): number => {
    const R = 6371e3; // Earth's radius in meters

    // Convert to radians
    const lat1 = (point.lat * Math.PI) / 180;
    const lng1 = (point.lng * Math.PI) / 180;
    const lat2 = (lineStart.lat * Math.PI) / 180;
    const lng2 = (lineStart.lng * Math.PI) / 180;
    const lat3 = (lineEnd.lat * Math.PI) / 180;
    const lng3 = (lineEnd.lng * Math.PI) / 180;

    // Calculate vectors
    const dLat21 = lat2 - lat1;
    const dLng21 = lng2 - lng1;
    const dLat32 = lat3 - lat2;
    const dLng32 = lng3 - lng2;
    const dLat31 = lat3 - lat1;
    const dLng31 = lng3 - lng1;

    // Calculate cross product and dot products
    const cross = dLat21 * dLng32 - dLng21 * dLat32;
    const dot1 = dLat21 * dLat32 + dLng21 * dLng32;
    const dot2 = dLat31 * dLat32 + dLng31 * dLng32;

    // Find closest point on line segment
    if (dot2 <= 0) {
        // Closest to end point
        return calculateDistance(point, lineEnd);
    } else if (dot1 >= 0) {
        // Closest to start point
        return calculateDistance(point, lineStart);
    } else {
        // Closest to some point on the line segment
        const segmentLength = Math.sqrt(dLat32 * dLat32 + dLng32 * dLng32);
        return (Math.abs(cross) * R) / segmentLength;
    }
};
// Helper function to get shapes data
const getShapesData = () => {
    if (!props.location) return [];

    if (props.location.data.shapes) {
        // New multi-shape format
        return props.location.data.shapes;
    } else if (props.location.data.points) {
        // Old single polygon format - convert to new format
        return [
            {
                name: props.location.name,
                type: 'polygon' as const,
                points: props.location.data.points,
                coordinates: props.location.data.coordinates || [],
                bounds: props.location.data.bounds || {
                    north: Math.max(
                        ...props.location.data.points.map((p) => p.lat),
                    ),
                    south: Math.min(
                        ...props.location.data.points.map((p) => p.lat),
                    ),
                    east: Math.max(
                        ...props.location.data.points.map((p) => p.lng),
                    ),
                    west: Math.min(
                        ...props.location.data.points.map((p) => p.lng),
                    ),
                },
                area_km2: props.location.data.area_km2,
            },
        ];
    }

    return [];
};

// Computed properties for multi-shape support
const isInsideOrNearAnyShape = computed(() => {
    if (!currentLocation.value) return false;

    const shapes = getShapesData();
    return shapes.some((shape) => {
        if (shape.type === 'polygon') {
            return isPointInPolygon(currentLocation.value!, shape.points);
        } else if (shape.type === 'line') {
            const distance = distanceToLine(
                currentLocation.value!,
                shape.points,
            );
            return distance <= 5; // 5 meters
        }
        return false;
    });
});

const insideShapeNames = computed(() => {
    if (!currentLocation.value) return [];

    const shapes = getShapesData();
    const result: string[] = [];

    shapes.forEach((shape) => {
        if (
            shape.type === 'polygon' &&
            isPointInPolygon(currentLocation.value!, shape.points)
        ) {
            result.push(shape.name);
        } else if (shape.type === 'line') {
            const distance = distanceToLine(
                currentLocation.value!,
                shape.points,
            );
            if (distance <= 5) {
                result.push(`${shape.name} (${distance.toFixed(1)}m)`);
            }
        }
    });

    return result;
});

// Function to navigate to specific shape
const navigateToShape = (shape: any) => {
    if (!map.value) return;

    const center = calculateShapeCenter(shape.points);

    // Set appropriate zoom level based on shape type and size
    let zoomLevel = 16;
    if (shape.type === 'polygon' && shape.area_km2) {
        // Adjust zoom based on area size
        if (shape.area_km2 > 1) zoomLevel = 14;
        else if (shape.area_km2 > 0.1) zoomLevel = 15;
        else zoomLevel = 17;
    } else if (shape.type === 'line' && shape.length_km) {
        // Adjust zoom based on line length
        if (shape.length_km > 5) zoomLevel = 13;
        else if (shape.length_km > 1) zoomLevel = 15;
        else zoomLevel = 17;
    }

    map.value.setView([center.lat, center.lng], zoomLevel, {
        animate: true,
        duration: 1,
    });

    // Disable auto-follow modes
    isFollowingUser.value = false;
    isOverviewActive.value = false;
    isFreeRoamActive.value = true;
    currentCameraMode.value = 'free-roam';
};
const distanceToNearestShape = computed(() => {
    if (!currentLocation.value) return null;

    const shapes = getShapesData();
    let minDistance = Infinity;

    shapes.forEach((shape) => {
        const center = calculateShapeCenter(shape.points);
        const distance = calculateDistance(currentLocation.value!, center);
        if (distance < minDistance) {
            minDistance = distance;
        }
    });

    return minDistance === Infinity ? null : minDistance;
});

const nearestShapeName = computed(() => {
    if (!currentLocation.value) return '';

    const shapes = getShapesData();
    let minDistance = Infinity;
    let nearestName = '';

    shapes.forEach((shape) => {
        const center = calculateShapeCenter(shape.points);
        const distance = calculateDistance(currentLocation.value!, center);
        if (distance < minDistance) {
            minDistance = distance;
            nearestName = shape.name;
        }
    });

    return nearestName;
});

// Check if user is inside a specific shape
const isInsideShape = (shape: any) => {
    if (!currentLocation.value || shape.type !== 'polygon') return false;
    return isPointInPolygon(currentLocation.value, shape.points);
};

// Calculate distance between two points in meters
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

// Calculate shape center (works for both polygons and lines)
const calculateShapeCenter = (
    points: Array<{ lat: number; lng: number }>,
): { lat: number; lng: number } => {
    const latSum = points.reduce((sum, point) => sum + point.lat, 0);
    const lngSum = points.reduce((sum, point) => sum + point.lng, 0);

    return {
        lat: latSum / points.length,
        lng: lngSum / points.length,
    };
};

// Point in polygon calculation (ray casting algorithm)
const isPointInPolygon = (
    point: { lat: number; lng: number },
    polygonPoints: Array<{ lat: number; lng: number }>,
) => {
    const x = point.lng;
    const y = point.lat;
    let inside = false;

    for (
        let i = 0, j = polygonPoints.length - 1;
        i < polygonPoints.length;
        j = i++
    ) {
        const xi = polygonPoints[i].lng;
        const yi = polygonPoints[i].lat;
        const xj = polygonPoints[j].lng;
        const yj = polygonPoints[j].lat;

        if (yi > y !== yj > y && x < ((xj - xi) * (y - yi)) / (yj - yi) + xi) {
            inside = !inside;
        }
    }

    return inside;
};

// Toggle free roam
const toggleFreeRoam = () => {
    isFreeRoamActive.value = !isFreeRoamActive.value;
    if (isFreeRoamActive.value) {
        isOverviewActive.value = false;
        isFollowingUser.value = false;
        currentCameraMode.value = 'free-roam';
    } else {
        showOverview();
    }
};

// Tile layer functions
const toggleTileSelector = () => {
    showTileSelector.value = !showTileSelector.value;
};

const selectTile = (tile: (typeof availableTiles)[0]) => {
    if (!map.value || currentTileLayer.value.id === tile.id) {
        showTileSelector.value = false;
        return;
    }

    // Remove current tile layer
    if (currentTileLayerRef.value) {
        map.value.removeLayer(currentTileLayerRef.value);
    }

    // Add new tile layer
    currentTileLayerRef.value = L.tileLayer(tile.url, {
        attribution: tile.attribution,
    }).addTo(map.value);

    // Update current tile
    currentTileLayer.value = tile;
    showTileSelector.value = false;
};
// Update the shape color logic in initMap function
const updateShapeColors = () => {
    if (!currentLocation.value) return;

    shapeElements.value.forEach(({ shape, data }) => {
        let isActive = false;

        if (data.type === 'polygon') {
            isActive = isPointInPolygon(currentLocation.value!, data.points);
        } else if (data.type === 'line') {
            const distance = distanceToLine(
                currentLocation.value!,
                data.points,
            );
            isActive = distance <= 5;
        }

        // Update shape color
        if (data.type === 'polygon') {
            (shape as L.Polygon).setStyle({
                color: isActive ? 'green' : 'blue',
                weight: 2,
                fillOpacity: isActive ? 0.3 : 0.2,
            });
        } else {
            (shape as L.Polyline).setStyle({
                color: isActive ? 'green' : 'red',
                weight: isActive ? 4 : 3,
                opacity: isActive ? 1 : 0.8,
            });
        }
    });
};
// Reset to initial state
const resetToInitialState = () => {
    // Clear current location data
    currentLocation.value = null;
    lastUpdated.value = 'Never';

    // Reset UI states
    isOverviewActive.value = false;
    isFollowingUser.value = false;
    isFreeRoamActive.value = false;
    isTrackingLocation.value = false;
    showConnectionLine.value = false;

    // Remove location markers from map
    if (map.value) {
        if (currentLocationMarker.value) {
            map.value.removeLayer(currentLocationMarker.value);
            currentLocationMarker.value = null;
        }
        if (currentLocationCircle.value) {
            map.value.removeLayer(currentLocationCircle.value);
            currentLocationCircle.value = null;
        }
        connectionLines.value.forEach((line) => {
            if (map.value) {
                map.value.removeLayer(line);
            }
        });
        connectionLines.value = [];

        // Remove any distance popups
        map.value.eachLayer((layer) => {
            if (
                layer instanceof L.Popup &&
                layer.getElement()?.classList.contains('distance-popup')
            ) {
                map.value?.removeLayer(layer);
            }
        });

        // Fit map back to all shapes bounds (initial state)
        const shapes = getShapesData();
        if (shapes.length > 0) {
            const allBounds = L.latLngBounds([]);
            shapes.forEach((shape) => {
                shape.points.forEach((point) => {
                    allBounds.extend([point.lat, point.lng]);
                });
            });

            if (allBounds.isValid()) {
                map.value.fitBounds(allBounds, {
                    padding: [20, 20],
                    animate: true,
                    duration: 0.8,
                });
            }
        }
    }
};

// Show overview function
const showOverview = () => {
    if (!map.value) return;

    // Set overview as active FIRST for immediate visual feedback
    isOverviewActive.value = true;
    isFollowingUser.value = false;
    isFreeRoamActive.value = false;

    currentCameraMode.value = 'overview';

    if (!map.value) return;

    const bounds = L.latLngBounds([]);

    // Include current location if available
    if (currentLocation.value) {
        bounds.extend([currentLocation.value.lat, currentLocation.value.lng]);
    }

    // Include all shape centers
    shapeCenters.value.forEach((center) => {
        bounds.extend([center.lat, center.lng]);
    });

    // Include all shape points
    const shapes = getShapesData();
    shapes.forEach((shape) => {
        shape.points.forEach((point) => {
            bounds.extend([point.lat, point.lng]);
        });
    });

    if (bounds.isValid()) {
        map.value.fitBounds(bounds, {
            padding: [50, 50],
            animate: true,
            duration: 0.8,
        });
    }
};

// Follow user function
const followUser = () => {
    if (!map.value || !currentLocation.value) return;

    // Set follow as active
    isFollowingUser.value = true;
    isOverviewActive.value = false;
    isFreeRoamActive.value = false;
    currentCameraMode.value = 'follow';

    // Immediate high-precision zoom
    const zoomLevel = locationState.value.isMoving
        ? trackingConfig.walkingZoom
        : trackingConfig.followingZoom;

    map.value.setView(
        [currentLocation.value.lat, currentLocation.value.lng],
        zoomLevel,
        {
            animate: true,
            duration: 0.8,
        },
    );
};

// Update camera based on active mode
const updateCameraView = () => {
    if (!map.value || !currentLocation.value) return;

    // Don't move camera if in free-roam mode
    if (isFreeRoamActive.value || currentCameraMode.value === 'free-roam') {
        return;
    }

    if (isFollowingUser.value) {
        // Determine zoom level based on movement and accuracy
        let targetZoom = trackingConfig.followingZoom;

        if (locationState.value.isMoving) {
            // Higher zoom when moving for better precision
            targetZoom = trackingConfig.walkingZoom;
        } else if (locationState.value.isHighAccuracy) {
            // High zoom for high accuracy GPS
            targetZoom = trackingConfig.walkingZoom;
        }

        // Smooth camera following with movement prediction
        const animationDuration = locationState.value.isMoving ? 0.2 : 0.5;

        // Use setView with smooth animation
        map.value.setView(
            [currentLocation.value.lat, currentLocation.value.lng],
            Math.max(targetZoom, map.value.getZoom()), // Don't zoom out automatically
            {
                animate: true,
                duration: animationDuration,
                easeLinearity: 0.1, // Smoother animation
            },
        );

        // Optional: Rotate map based on movement heading (uncomment if desired)
        if (currentLocation.value.heading && locationState.value.isMoving) {
            map.value.setBearing(currentLocation.value.heading);
        }
    }
};

// Toggle connection line
const toggleConnectionLine = () => {
    showConnectionLine.value = !showConnectionLine.value;
    updateConnectionLines();
};

// Update connection lines
const updateConnectionLines = () => {
    if (!map.value) return;

    // Remove existing lines
    connectionLines.value.forEach((line) => {
        map.value!.removeLayer(line);
    });
    connectionLines.value = [];

    // Remove any existing distance popup
    map.value.eachLayer((layer) => {
        if (
            layer instanceof L.Popup &&
            layer.getElement()?.classList.contains('distance-popup')
        ) {
            map.value?.removeLayer(layer);
        }
    });

    // Add new lines if enabled and current location exists
    if (showConnectionLine.value && currentLocation.value) {
        shapeCenters.value.forEach((center) => {
            const line = L.polyline(
                [
                    [currentLocation.value!.lat, currentLocation.value!.lng],
                    [center.lat, center.lng],
                ],
                {
                    color: isInsideOrNearAnyShape.value ? 'green' : 'red',
                    weight: 2,
                    dashArray: '5, 5',
                    opacity: 0.7,
                },
            ).addTo(map.value!);

            connectionLines.value.push(line);
        });
    }
};

// Initialize map
const initMap = () => {
    if (!mapContainer.value || !props.location) return;

    map.value = L.map(mapContainer.value);

    // Add initial tile layer
    currentTileLayerRef.value = L.tileLayer(currentTileLayer.value.url, {
        attribution: currentTileLayer.value.attribution,
    }).addTo(map.value);

    const shapes = getShapesData();
    if (shapes.length === 0) return;

    // Process each shape
    shapes.forEach((shapeData, index) => {
        // Calculate shape center
        const center = calculateShapeCenter(shapeData.points);
        shapeCenters.value.push({
            lat: center.lat,
            lng: center.lng,
            name: shapeData.name,
        });

        // Add center marker
        const centerMarker = L.marker([center.lat, center.lng], {
            icon: L.icon({
                iconUrl: markerIcon,
                iconRetinaUrl: markerRetina,
                shadowUrl: markerShadow,
                iconSize: [20, 32],
                iconAnchor: [10, 32],
                popupAnchor: [1, -30],
                shadowSize: [32, 32],
            }),
        }).addTo(map.value!);

        centerMarker.bindPopup(`
            <div>
                <strong>${shapeData.name}</strong><br>
                Type: ${shapeData.type}<br>
                ${
                    shapeData.type === 'polygon'
                        ? `Area: ${shapeData.area_km2?.toFixed(2) || 'Unknown'} km²`
                        : `Length: ${shapeData.length_km?.toFixed(2) || 'Unknown'} km`
                }<br>
                Points: ${shapeData.points.length}<br>
                Lat: ${center.lat.toFixed(6)}<br>
                Lng: ${center.lng.toFixed(6)}
            </div>
        `);

        shapeCenterMarkers.value.push(centerMarker);

        // Create shape on map
        const shapeCoords = shapeData.points.map(
            (point) => [point.lat, point.lng] as [number, number],
        );

        let shapeElement: L.Polygon | L.Polyline;

        if (shapeData.type === 'polygon') {
            shapeElement = L.polygon(shapeCoords, {
                color: 'blue',
                weight: 2,
                fillOpacity: 0.2,
            }).addTo(map.value!);
        } else {
            shapeElement = L.polyline(shapeCoords, {
                color: 'green',
                weight: 3,
                opacity: 0.8,
            }).addTo(map.value!);
        }

        shapeElement.bindPopup(`
            <div>
                <strong>${shapeData.name}</strong><br>
                Type: ${shapeData.type}<br>
                ${
                    shapeData.type === 'polygon'
                        ? `Area: ${shapeData.area_km2?.toFixed(2) || 'Unknown'} km²`
                        : `Length: ${shapeData.length_km?.toFixed(2) || 'Unknown'} km`
                }<br>
                Points: ${shapeData.points.length}
            </div>
        `);

        shapeElements.value.push({
            shape: shapeElement,
            data: shapeData,
        });
    });

    // Fit map to all shapes bounds initially
    const allBounds = L.latLngBounds([]);
    shapes.forEach((shape) => {
        shape.points.forEach((point) => {
            allBounds.extend([point.lat, point.lng]);
        });
    });

    if (allBounds.isValid()) {
        map.value.fitBounds(allBounds);
    }

    // Interaction listeners to detect manual mode
    map.value.on('dragstart drag zoomstart zoom movestart move', () => {
        // User is manually interacting with the map - disable auto modes immediately
        isOverviewActive.value = false;
        isFollowingUser.value = false;
        showTileSelector.value = false; // Also close tile selector
    });
};

// Get current location
const getCurrentLocation = () => {
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser.');
        return;
    }

    if (!isTrackingLocation.value) {
        isGettingLocation.value = true;
    }

    // Enhanced geolocation options for mobile precision
    const options = {
        enableHighAccuracy: trackingConfig.enableHighAccuracy,
        timeout: trackingConfig.timeout,
        maximumAge: trackingConfig.maximumAge,
    };
    console.log('Requesting location with options:', options);
    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            const heading = position.coords.heading; // Device compass heading
            const speed = position.coords.speed; // Device-reported speed

            const newLocation = { lat, lng, accuracy };

            // Detect movement and update state
            detectMovement(newLocation);

            // Update current location
            currentLocation.value = {
                lat,
                lng,
                accuracy,
                heading: heading || locationState.value.heading,
                speed: speed || locationState.value.movementSpeed,
            };

            lastUpdated.value = new Date().toLocaleTimeString();

            // Always update marker (this works in all modes)
            updateLocationMarker();

            // Only update camera view if NOT in free-roam mode
            if (
                !isFreeRoamActive.value &&
                currentCameraMode.value !== 'free-roam'
            ) {
                updateCameraView();
            }

            // Update connection lines
            updateConnectionLines();

            isGettingLocation.value = false;
        },
        (error) => {
            console.error('Geolocation error:', error);
            isGettingLocation.value = false;

            let errorMessage = 'Unable to get your location.';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage =
                        'Location access denied. Please enable location services.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage =
                        'Location information is unavailable. Check your GPS signal.';
                    break;
                case error.TIMEOUT:
                    errorMessage =
                        'Location request timed out. Trying again...';
                    // Automatically retry on timeout
                    setTimeout(() => {
                        if (isTrackingLocation.value) {
                            getCurrentLocation();
                        }
                    }, 2000);
                    break;
            }
            console.warn(errorMessage);
        },
        options,
    );
};

// Update location marker on map
const updateLocationMarker = () => {
    if (!map.value || !currentLocation.value) return;

    // Remove existing markers
    if (currentLocationMarker.value) {
        map.value.removeLayer(currentLocationMarker.value);
    }
    if (currentLocationCircle.value) {
        map.value.removeLayer(currentLocationCircle.value);
    }

    // Determine marker color based on status and movement
    let markerColor = isInsideOrNearAnyShape.value ? 'green' : 'red';
    if (locationState.value.isMoving) {
        markerColor = isInsideOrNearAnyShape.value ? 'darkgreen' : 'darkred';
    }

    // Create enhanced marker icon based on movement state
    const markerSize = locationState.value.isMoving ? [30, 45] : [25, 41];
    const customIcon = L.icon({
        iconUrl: markerIcon,
        iconRetinaUrl: markerRetina,
        shadowUrl: markerShadow,
        iconSize: markerSize,
        iconAnchor: [markerSize[0] / 2, markerSize[1]],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    });

    currentLocationMarker.value = L.marker(
        [currentLocation.value.lat, currentLocation.value.lng],
        { icon: customIcon },
    ).addTo(map.value);

    // Enhanced popup with movement information
    const statusText = isInsideOrNearAnyShape.value
        ? `Inside/Near: ${insideShapeNames.value.join(', ')}`
        : 'Outside all areas';

    const movementText = locationState.value.isMoving
        ? `Moving at ${(locationState.value.movementSpeed * 3.6).toFixed(1)} km/h`
        : 'Stationary';

    const accuracyText = locationState.value.isHighAccuracy
        ? '📍 High precision GPS'
        : '📶 Standard GPS';

    currentLocationMarker.value.bindPopup(`
        <div>
            <strong>📱 Your Location</strong><br>
            Status: <span style="color: ${markerColor}; font-weight: bold;">${statusText}</span><br>
            Movement: <span style="color: blue;">${movementText}</span><br>
            GPS: ${accuracyText}<br>
            ${currentLocation.value.heading ? `Heading: ${currentLocation.value.heading.toFixed(0)}°<br>` : ''}
            Distance to nearest: ${distanceToNearestShape.value?.toFixed(0)}m to ${nearestShapeName.value}<br>
            Lat: ${currentLocation.value.lat.toFixed(6)}<br>
            Lng: ${currentLocation.value.lng.toFixed(6)}<br>
            Accuracy: ±${Math.round(currentLocation.value.accuracy || 0)}m
        </div>
    `);

    // Enhanced accuracy circle with movement indication
    if (currentLocation.value.accuracy) {
        const circleColor = locationState.value.isHighAccuracy
            ? 'blue'
            : 'orange';
        const circleOpacity = locationState.value.isMoving ? 0.3 : 0.1;

        currentLocationCircle.value = L.circle(
            [currentLocation.value.lat, currentLocation.value.lng],
            {
                radius: Math.min(currentLocation.value.accuracy, 20), // Cap at 20m for visibility
                color: circleColor,
                fillColor: circleColor,
                fillOpacity: circleOpacity,
                weight: locationState.value.isMoving ? 2 : 1,
                dashArray: locationState.value.isMoving ? '5,5' : undefined,
            },
        ).addTo(map.value);
    }

    // Update shape colors
    updateShapeColors();
};

// Toggle location tracking
const toggleLocationTracking = () => {
    if (isTrackingLocation.value) {
        // Stop tracking
        if (trackingInterval.value) {
            clearInterval(trackingInterval.value);
            trackingInterval.value = null;
            currentCameraMode.value = '';
        }

        // Reset location state
        locationState.value = {
            isMoving: false,
            lastMovementTime: null,
            movementSpeed: 0,
            heading: null,
            lastPosition: null,
            accuracy: null,
            isHighAccuracy: false,
        };

        // Reset to initial state
        resetToInitialState();
        console.log('Location tracking stopped.');
    } else {
        // Start tracking
        isTrackingLocation.value = true;

        // Get initial location
        getCurrentLocation();

        // Set up dynamic tracking interval
        trackingInterval.value = setInterval(() => {
            if (!isTrackingLocation.value) return;
            getCurrentLocation();
        }, 2000); // Initial quick fetch

        // Auto-start with overview and connection lines
        setTimeout(() => {
            showOverview();
            showConnectionLine.value = true;
            updateConnectionLines();
        }, 500);
    }
};

// Lifecycle
onMounted(() => {
    if (props.location && !props.error) {
        initMap();
    }
});

onUnmounted(() => {
    if (trackingInterval.value) {
        clearInterval(trackingInterval.value);
    }
    if (map.value) {
        map.value.remove();
    }
});
</script>
