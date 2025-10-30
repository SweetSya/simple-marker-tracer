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
                        isTrackingLocation && isInside
                            ? '!border-green-500'
                            : isTrackingLocation && !isInside
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
                                            isInside
                                                ? 'bg-green-400'
                                                : 'bg-red-400',
                                        ]"
                                    ></div>
                                    <span
                                        :class="[
                                            'text-sm font-medium',
                                            isInside
                                                ? 'text-green-800'
                                                : 'text-red-800',
                                        ]"
                                    >
                                        {{
                                            isInside
                                                ? 'Inside Area'
                                                : 'Outside Area'
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center space-x-4">
                                <div class="text-xs text-gray-500 sm:text-sm">
                                    Last updated: {{ lastUpdated }}
                                </div>
                                <div
                                    v-if="distanceToCenter !== null"
                                    class="text-xs text-gray-500 sm:text-sm"
                                >
                                    Distance: {{ distanceToCenter.toFixed(0) }}m
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
                                isGettingLocation
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
                            {{ showConnectionLine ? 'Hide Line' : 'Show Line' }}
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
                    <p v-show="!isTrackingLocation" class="text-white">
                        Start tracking to reveal your location
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

// Props
const props = defineProps<{
    error: string | null;
    location: {
        id: string;
        name: string;
        data: {
            type: string;
            points: Array<{ lat: number; lng: number }>;
            coordinates: number[][];
            bounds: {
                north: number;
                south: number;
                east: number;
                west: number;
            };
            area_km2?: number;
            metadata: any;
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
const polygon = ref<L.Polygon | null>(null);
const polygonCenter = ref<{ lat: number; lng: number } | null>(null);
const polygonCenterMarker = ref<L.Marker | null>(null);
const currentLocationMarker = ref<L.Marker | null>(null);
const currentLocationCircle = ref<L.Circle | null>(null);
const connectionLine = ref<L.Polyline | null>(null);
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

// Computed
const isInside = computed(() => {
    if (!currentLocation.value || !props.location?.data.points) return false;
    return isPointInPolygon(currentLocation.value, props.location.data.points);
});

const distanceToCenter = computed(() => {
    if (!currentLocation.value || !polygonCenter.value) return null;
    return calculateDistance(currentLocation.value, polygonCenter.value);
});

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

// Calculate polygon center
const calculatePolygonCenter = (
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

// Reset to initial state
const resetToInitialState = () => {
    // Clear current location data
    currentLocation.value = null;
    lastUpdated.value = 'Never';

    // Reset UI states
    isOverviewActive.value = false;
    isFollowingUser.value = false;
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
        if (connectionLine.value) {
            map.value.removeLayer(connectionLine.value);
            connectionLine.value = null;
        }

        // Remove any distance popups
        map.value.eachLayer((layer) => {
            if (
                layer instanceof L.Popup &&
                layer.getElement()?.classList.contains('distance-popup')
            ) {
                map.value?.removeLayer(layer);
            }
        });

        // Fit map back to polygon bounds (initial state)
        if (polygon.value) {
            map.value.fitBounds(polygon.value.getBounds(), {
                padding: [20, 20],
                animate: true,
                duration: 0.8,
            });
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

    if (currentLocation.value && polygonCenter.value) {
        const bounds = L.latLngBounds([
            [currentLocation.value.lat, currentLocation.value.lng],
            [polygonCenter.value.lat, polygonCenter.value.lng],
        ]);

        // Extend bounds to include the polygon
        if (polygon.value) {
            bounds.extend(polygon.value.getBounds());
        }

        map.value.fitBounds(bounds, {
            padding: [50, 50],
            animate: true,
            duration: 0.8,
        });
    } else if (polygon.value) {
        map.value.fitBounds(polygon.value.getBounds(), {
            padding: [20, 20],
            animate: true,
            duration: 0.8,
        });
    }
};

// Follow user function
const followUser = () => {
    if (!map.value || !currentLocation.value) return;

    // Set follow as active FIRST for immediate visual feedback
    isFollowingUser.value = true;
    isOverviewActive.value = false;
    isFreeRoamActive.value = false;

    currentCameraMode.value = 'follow';

    if (!map.value || !currentLocation.value) return;

    // Zoom in on user location with nice zoom level
    map.value.setView(
        [currentLocation.value.lat, currentLocation.value.lng],
        18, // Nice zoom level for following user
        {
            animate: true,
            duration: 1,
        },
    );
};

// Update camera based on active mode
const updateCameraView = () => {
    if (!map.value || !currentLocation.value) return;

    if (isFollowingUser.value) {
        // Delay camera update slightly to prevent interference with line updates
        setTimeout(() => {
            if (map.value && currentLocation.value && isFollowingUser.value) {
                // Follow user mode - keep camera centered on user
                map.value.setView(
                    [currentLocation.value.lat, currentLocation.value.lng],
                    map.value.getZoom() < 16 ? 18 : map.value.getZoom(), // Maintain good zoom or set to 18
                    {
                        animate: true,
                        duration: 0.3, // Shorter duration for smoother updates
                    },
                );
            }
        }, 50);
    }
    // Overview mode is handled manually when button is clicked
};

// Toggle connection line
const toggleConnectionLine = () => {
    showConnectionLine.value = !showConnectionLine.value;
    updateConnectionLine();
};

// Update connection line
const updateConnectionLine = () => {
    if (!map.value) return;

    // Remove existing line and popup
    if (connectionLine.value) {
        map.value.removeLayer(connectionLine.value);
        connectionLine.value = null;
    }

    // Remove any existing distance popup
    map.value.eachLayer((layer) => {
        if (
            layer instanceof L.Popup &&
            layer.getElement()?.classList.contains('distance-popup')
        ) {
            map.value?.removeLayer(layer);
        }
    });

    // Add new line if enabled and both points exist
    if (
        showConnectionLine.value &&
        currentLocation.value &&
        polygonCenter.value
    ) {
        connectionLine.value = L.polyline(
            [
                [currentLocation.value.lat, currentLocation.value.lng],
                [polygonCenter.value.lat, polygonCenter.value.lng],
            ],
            {
                color: isInside.value ? 'green' : 'red',
                weight: 2,
                dashArray: '5, 5',
                opacity: 0.7,
            },
        ).addTo(map.value);
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

    // Calculate polygon center
    polygonCenter.value = calculatePolygonCenter(props.location.data.points);

    // polygon center marker
    if (polygonCenter.value) {
        polygonCenterMarker.value = L.marker(
            [polygonCenter.value.lat, polygonCenter.value.lng],
            {
                icon: L.icon({
                    iconUrl: markerIcon,
                    iconRetinaUrl: markerRetina,
                    shadowUrl: markerShadow,
                    iconSize: [20, 32],
                    iconAnchor: [10, 32],
                    popupAnchor: [1, -30],
                    shadowSize: [32, 32],
                }),
            },
        ).addTo(map.value);

        polygonCenterMarker.value.bindPopup(`
            <div>
                <strong>Area Center</strong><br>
                ${props.location.name}<br>
                Lat: ${polygonCenter.value.lat.toFixed(6)}<br>
                Lng: ${polygonCenter.value.lng.toFixed(6)}
            </div>
        `);
    }

    // polygon
    const polygonCoords = props.location.data.points.map(
        (point) => [point.lat, point.lng] as [number, number],
    );

    polygon.value = L.polygon(polygonCoords, {
        color: 'blue',
        weight: 2,
        fillOpacity: 0.2,
    }).addTo(map.value);

    polygon.value.bindPopup(`
        <div>
            <strong>${props.location.name}</strong><br>
            Area: ${props.location.data.area_km2?.toFixed(2) || 'Unknown'} km²<br>
            Points: ${props.location.data.points.length}
        </div>
    `);

    // Fit map to polygon bounds initially
    map.value.fitBounds(polygon.value.getBounds());

    // interaction listeners to detect manual mode - use multiple events
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

    isGettingLocation.value = true;

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            currentLocation.value = { lat, lng, accuracy };
            lastUpdated.value = new Date().toLocaleTimeString();

            // Update marker first
            updateLocationMarker();

            // Then update camera view
            updateCameraView();

            // Finally update connection line (this prevents popup interference)
            updateConnectionLine();

            isGettingLocation.value = false;
        },
        (error) => {
            console.error('Geolocation error:', error);
            isGettingLocation.value = false;

            let errorMessage = 'Unable to get your location.';
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
            alert(errorMessage);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        },
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

    // Add new marker
    const markerColor = isInside.value ? 'green' : 'red';

    currentLocationMarker.value = L.marker([
        currentLocation.value.lat,
        currentLocation.value.lng,
    ]).addTo(map.value);

    currentLocationMarker.value.bindPopup(`
        <div>
            <strong>Your Location</strong><br>
            Status: <span style="color: ${markerColor};">${isInside.value ? 'Inside Area' : 'Outside Area'}</span><br>
            Distance to center: ${distanceToCenter.value?.toFixed(0)}m<br>
            Lat: ${currentLocation.value.lat.toFixed(6)}<br>
            Lng: ${currentLocation.value.lng.toFixed(6)}<br>
            ${currentLocation.value.accuracy ? `Accuracy: ±${Math.round(currentLocation.value.accuracy)}m` : ''}
        </div>
    `);

    // Add accuracy circle if available
    if (currentLocation.value.accuracy) {
        currentLocationCircle.value = L.circle(
            [currentLocation.value.lat, currentLocation.value.lng],
            {
                radius:
                    currentLocation.value.accuracy > 15
                        ? 15
                        : currentLocation.value.accuracy,
                color: markerColor,
                fillColor: markerColor,
                fillOpacity: 0.1,
                weight: 1,
            },
        ).addTo(map.value);
    }
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
        isTrackingLocation.value = false;

        // Reset to initial state when stopping
        resetToInitialState();
    } else {
        // Start tracking
        getCurrentLocation(); // Get initial location
        trackingInterval.value = setInterval(getCurrentLocation, 3000); // Every 3 seconds
        isTrackingLocation.value = true;
        // Start Center and show line
        setTimeout(() => {
            showOverview();
            showConnectionLine.value = true;
            updateConnectionLine();
        }, 300);
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
