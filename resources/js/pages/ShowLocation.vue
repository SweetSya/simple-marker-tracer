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
            class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
        >
            <!-- Status Panel -->
            <div class="mb-6">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                Location Status
                            </h2>
                            <div class="mt-2 flex items-center space-x-4">
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
                                <div class="text-sm text-gray-500">
                                    Last updated: {{ lastUpdated }}
                                </div>
                            </div>
                        </div>
                        <button
                            @click="toggleLocationTracking"
                            :disabled="isGettingLocation"
                            :class="[
                                'rounded-md px-4 py-2 text-sm font-medium transition-colors',
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

                    <div
                        v-if="currentLocation"
                        class="mt-4 text-sm text-gray-600"
                    >
                        Current Position: {{ currentLocation.lat.toFixed(6) }},
                        {{ currentLocation.lng.toFixed(6) }}
                        <span v-if="currentLocation.accuracy" class="ml-2">
                            (±{{ Math.round(currentLocation.accuracy) }}m)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="rounded-lg bg-white shadow">
                <div
                    ref="mapContainer"
                    class="w-full rounded-lg"
                    style="height: 500px"
                ></div>
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
            metadata: any;
        };
        share_code: string;
    } | null;
}>();

// Reactive variables
const mapContainer = ref<HTMLElement>();
const map = ref<L.Map | null>(null);
const polygon = ref<L.Polygon | null>(null);
const currentLocationMarker = ref<L.Marker | null>(null);
const currentLocationCircle = ref<L.Circle | null>(null);
const currentLocation = ref<{
    lat: number;
    lng: number;
    accuracy?: number;
} | null>(null);
const isTrackingLocation = ref(false);
const isGettingLocation = ref(false);
const lastUpdated = ref('Never');
const trackingInterval = ref<number | null>(null);

// Computed
const isInside = computed(() => {
    if (!currentLocation.value || !props.location?.data.points) return false;
    return isPointInPolygon(currentLocation.value, props.location.data.points);
});

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

// Initialize map
const initMap = () => {
    if (!mapContainer.value || !props.location) return;

    map.value = L.map(mapContainer.value);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map.value);

    // Add polygon
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

    // Fit map to polygon bounds
    map.value.fitBounds(polygon.value.getBounds());
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

            updateLocationMarker();
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
            timeout: 10000,
            maximumAge: 60000, // 1 minute
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
            Lat: ${currentLocation.value.lat.toFixed(6)}<br>
            Lng: ${currentLocation.value.lng.toFixed(6)}<br>
            ${currentLocation.value.accuracy ? `Accuracy: ±${Math.round(currentLocation.value.accuracy)}m` : ''}
        </div>
    `);

    // Add accuracy circle if available
    if (currentLocation.value.accuracy) {
        console.log(
            'Adding accuracy circle with radius:',
            currentLocation.value.accuracy,
        );
        currentLocationCircle.value = L.circle(
            [currentLocation.value.lat, currentLocation.value.lng],
            {
                radius: currentLocation.value.accuracy,
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
        }
        isTrackingLocation.value = false;
    } else {
        // Start tracking
        getCurrentLocation(); // Get initial location
        trackingInterval.value = setInterval(getCurrentLocation, 2000); // Every 2 seconds
        isTrackingLocation.value = true;
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
});
</script>
