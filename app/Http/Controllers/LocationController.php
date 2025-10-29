<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Show user's locations
     */
    public function index(Request $request)
    {
        try {
            $locations = Location::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($location) {
                    return [
                        'id' => $location->id,
                        'name' => $location->name,
                        'share_code' => $location->share_code,
                        'share_expires_at' => $location->share_expires_at,
                        'created_at' => $location->created_at,
                        'updated_at' => $location->updated_at,
                        'data' => $location->data,
                        'is_expired' => $location->share_expires_at < now(),
                        'area_km2' => $location->data['area_km2'] ?? 0,
                        'points_count' => count($location->data['points'] ?? []),
                    ];
                });

            return Inertia::render('Auth/HistoryMarker', [
                'locations' => $locations
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading user locations: ' . $e->getMessage());

            return Inertia::render('Auth/HistoryMarker', [
                'locations' => [],
                'error' => 'Unable to load locations'
            ]);
        }
    }

    /**
     * Toggle marker expiry
     */
    public function toggleExpiry(Request $request, $id)
    {
        try {
            $location = Location::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $isCurrentlyExpired = $location->share_expires_at < now();

            if ($isCurrentlyExpired) {
                // Extend expiry by 7 days from now
                $location->share_expires_at = now()->addDays(7);
                $message = 'Marker expiry extended by 7 days';
            } else {
                // Set to expired (past date)
                $location->share_expires_at = now()->subDay();
                $message = 'Marker has been expired';
            }

            $location->save();

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_expiry' => $location->share_expires_at,
                'is_expired' => $location->share_expires_at < now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling marker expiry: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update marker expiry'
            ], 500);
        }
    }
    /**
     * Show shared location by code
     */
    public function showSharedLocation(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return Inertia::render('ShowLocation', [
                'error' => 'No location code provided',
                'location' => null
            ]);
        }

        try {
            $location = Location::where('share_code', $code)
                ->where('share_expires_at', '>', now())
                ->first();

            if (!$location) {
                return Inertia::render('ShowLocation', [
                    'error' => 'Location not found or has expired',
                    'location' => null
                ]);
            }

            return Inertia::render('ShowLocation', [
                'error' => null,
                'location' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'data' => $location->data,
                    'share_code' => $location->share_code,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading shared location: ' . $e->getMessage());

            return Inertia::render('ShowLocation', [
                'error' => 'Unable to load location',
                'location' => null
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'polygons' => 'required|array|min:1',
                'polygons.*.name' => 'required|string|max:255',
                'polygons.*.points' => 'required|array|min:3',
                'polygons.*.points.*.lat' => 'required|numeric|between:-90,90',
                'polygons.*.points.*.lng' => 'required|numeric|between:-180,180',
                'polygons.*.coordinates' => 'required|array|min:3',
                'polygons.*.area_km2' => 'required|numeric|min:0',
                'polygons.*.bounds' => 'required|array',
                'polygons.*.bounds.north' => 'required|numeric|between:-90,90',
                'polygons.*.bounds.south' => 'required|numeric|between:-90,90',
                'polygons.*.bounds.east' => 'required|numeric|between:-180,180',
                'polygons.*.bounds.west' => 'required|numeric|between:-180,180',
                'metadata' => 'required|array',
                'metadata.totalPolygons' => 'required|integer|min:1',
                'metadata.totalPolygonPoints' => 'required|integer|min:3',
                'metadata.mapType' => 'required|string|in:osm,satellite,terrain',
                'metadata.createdAt' => 'required|string',
            ]);

            $savedLocations = [];

            foreach ($validated['polygons'] as $polygon) {
                $locationData = [
                    'type' => 'polygon',
                    'points' => $polygon['points'],
                    'coordinates' => $polygon['coordinates'],
                    'area_km2' => $polygon['area_km2'],
                    'bounds' => $polygon['bounds'],
                    'metadata' => [
                        'mapType' => $validated['metadata']['mapType'],
                        'createdAt' => $validated['metadata']['createdAt'],
                        'mapCenter' => $validated['metadata']['mapCenter'] ?? null,
                        'mapZoom' => $validated['metadata']['mapZoom'] ?? null,
                    ]
                ];

                $location = Location::create([
                    'user_id' => $request->user()->id,
                    'name' => $polygon['name'],
                    'data' => $locationData,
                    'share_code' => Str::random(52),
                    'share_expires_at' => now()->addDays(30),
                ]);

                $savedLocations[] = [
                    'id' => $location->id,
                    'name' => $location->name,
                    'share_code' => $location->share_code,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Locations saved successfully',
                'data' => [
                    'saved_count' => count($savedLocations),
                    'locations' => $savedLocations
                ]
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Error saving locations: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save locations',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
