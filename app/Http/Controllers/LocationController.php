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
                    $data = $location->data;

                    // Handle both old single polygon format and new multi-shape format
                    if (isset($data['shapes'])) {
                        // New format with multiple shapes
                        $totalArea = 0;
                        $totalLength = 0;
                        $totalPoints = 0;
                        $shapeTypes = [];

                        foreach ($data['shapes'] as $shape) {
                            $totalPoints += count($shape['points'] ?? []);
                            $shapeTypes[] = $shape['type'];

                            if ($shape['type'] === 'polygon' && isset($shape['area_km2'])) {
                                $totalArea += $shape['area_km2'];
                            }
                            if ($shape['type'] === 'line' && isset($shape['length_km'])) {
                                $totalLength += $shape['length_km'];
                            }
                        }

                        return [
                            'id' => $location->id,
                            'name' => $location->name,
                            'share_code' => $location->share_code,
                            'share_expires_at' => $location->share_expires_at,
                            'created_at' => $location->created_at,
                            'updated_at' => $location->updated_at,
                            'data' => $location->data,
                            'is_expired' => $location->share_expires_at < now(),
                            'total_area_km2' => $totalArea,
                            'total_length_km' => $totalLength,
                            'total_points' => $totalPoints,
                            'shapes_count' => count($data['shapes']),
                            'shape_types' => array_unique($shapeTypes),
                            'format' => 'multi-shape'
                        ];
                    } else {
                        // Old format with single polygon
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
                            'format' => 'single-polygon'
                        ];
                    }
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
     * Extend expiry of a location by specified days
     */
    public function extendExpiry(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'days' => 'required|integer|min:1|max:365'
            ]);

            $location = Location::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            // Extend expiry by the specified days from now
            $location->share_expires_at = now()->addDays($validated['days']);
            $location->save();

            return response()->json([
                'success' => true,
                'message' => "Collection expiry extended by {$validated['days']} days",
                'new_expiry' => $location->share_expires_at,
                'is_expired' => false
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error extending collection expiry: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to extend collection expiry'
            ], 500);
        }
    }

    /**
     * Update collection name
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:1'
            ]);

            $location = Location::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $oldName = $location->name;
            $location->update([
                'name' => trim($validated['name'])
            ]);

            return response()->json([
                'success' => true,
                'message' => "Collection name updated from '{$oldName}' to '{$location->name}'",
                'data' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'updated_at' => $location->updated_at
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid collection name',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating location name: ' . $e->getMessage(), [
                'location_id' => $id,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update collection name'
            ], 500);
        }
    }

    /**
     * Delete a location/collection
     */
    public function destroy(Request $request, $id)
    {
        try {
            $location = Location::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $locationName = $location->name;
            $shareCode = $location->share_code;

            // Log the deletion for audit purposes
            Log::info('Location deleted by user', [
                'location_id' => $location->id,
                'location_name' => $locationName,
                'share_code' => $shareCode,
                'user_id' => $request->user()->id,
                'deleted_at' => now()
            ]);

            $location->delete();

            return response()->json([
                'success' => true,
                'message' => "Collection '{$locationName}' has been permanently deleted"
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Collection not found or you do not have permission to delete it'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting location: ' . $e->getMessage(), [
                'location_id' => $id,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete collection. Please try again.'
            ], 500);
        }
    }

    /**
     * Toggle marker expiry (legacy method)
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
                $message = 'Collection expiry extended by 7 days';
            } else {
                // Set to expired (past date)
                $location->share_expires_at = now()->subDay();
                $message = 'Collection has been expired';
            }

            $location->save();

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_expiry' => $location->share_expires_at,
                'is_expired' => $location->share_expires_at < now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling collection expiry: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update collection expiry'
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

    /**
     * Store new shape collection
     */
    public function store(Request $request)
    {
        try {
            // Updated validation for new multi-shape format
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'shapes' => 'required|array|min:1',
                'shapes.*.name' => 'required|string|max:255',
                'shapes.*.type' => 'required|string|in:line,polygon',
                'shapes.*.points' => 'required|array|min:2', // Lines need min 2, polygons min 3 (validated separately)
                'shapes.*.points.*.lat' => 'required|numeric|between:-90,90',
                'shapes.*.points.*.lng' => 'required|numeric|between:-180,180',
                'shapes.*.coordinates' => 'required|array|min:2',
                'shapes.*.bounds' => 'required|array',
                'shapes.*.bounds.north' => 'required|numeric|between:-90,90',
                'shapes.*.bounds.south' => 'required|numeric|between:-90,90',
                'shapes.*.bounds.east' => 'required|numeric|between:-180,180',
                'shapes.*.bounds.west' => 'required|numeric|between:-180,180',
                'shapes.*.area_km2' => 'nullable|numeric|min:0', // Only for polygons
                'shapes.*.length_km' => 'nullable|numeric|min:0', // Only for lines
                'metadata' => 'required|array',
                'metadata.totalShapes' => 'required|integer|min:1',
                'metadata.totalPoints' => 'required|integer|min:2',
                'metadata.shapeTypes' => 'required|array',
                'metadata.mapType' => 'required|string|in:osm,satellite,terrain',
                'metadata.createdAt' => 'required|string',
            ]);

            // Additional validation for shape-specific requirements
            foreach ($validated['shapes'] as $index => $shape) {
                if ($shape['type'] === 'polygon') {
                    if (count($shape['points']) < 3) {
                        return response()->json([
                            'success' => false,
                            'message' => "Polygon '{$shape['name']}' must have at least 3 points",
                            'error' => 'validation_error'
                        ], 422);
                    }
                    if (!isset($shape['area_km2'])) {
                        return response()->json([
                            'success' => false,
                            'message' => "Polygon '{$shape['name']}' must have area calculation",
                            'error' => 'validation_error'
                        ], 422);
                    }
                } elseif ($shape['type'] === 'line') {
                    if (count($shape['points']) < 2) {
                        return response()->json([
                            'success' => false,
                            'message' => "Line '{$shape['name']}' must have at least 2 points",
                            'error' => 'validation_error'
                        ], 422);
                    }
                    if (!isset($shape['length_km'])) {
                        return response()->json([
                            'success' => false,
                            'message' => "Line '{$shape['name']}' must have length calculation",
                            'error' => 'validation_error'
                        ], 422);
                    }
                }
            }

            // Calculate overall bounds for the entire collection
            $allPoints = [];
            foreach ($validated['shapes'] as $shape) {
                $allPoints = array_merge($allPoints, $shape['points']);
            }

            $overallBounds = [
                'north' => max(array_column($allPoints, 'lat')),
                'south' => min(array_column($allPoints, 'lat')),
                'east' => max(array_column($allPoints, 'lng')),
                'west' => min(array_column($allPoints, 'lng')),
            ];

            // Create the location data structure
            $locationData = [
                'format' => 'multi-shape',
                'shapes' => $validated['shapes'],
                'bounds' => $overallBounds,
                'metadata' => array_merge($validated['metadata'], [
                    'mapCenter' => $validated['metadata']['mapCenter'] ?? null,
                    'mapZoom' => $validated['metadata']['mapZoom'] ?? null,
                ])
            ];

            $location = Location::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'data' => $locationData,
                'share_code' => Str::random(52),
                'share_expires_at' => now()->addDays(30),
            ]);

            // Calculate summary statistics
            $totalArea = 0;
            $totalLength = 0;
            $polygonCount = 0;
            $lineCount = 0;

            foreach ($validated['shapes'] as $shape) {
                if ($shape['type'] === 'polygon') {
                    $polygonCount++;
                    $totalArea += $shape['area_km2'] ?? 0;
                } else {
                    $lineCount++;
                    $totalLength += $shape['length_km'] ?? 0;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Shape collection saved successfully',
                'data' => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'share_code' => $location->share_code,
                    'share_expires_at' => $location->share_expires_at,
                    'summary' => [
                        'total_shapes' => count($validated['shapes']),
                        'polygons' => $polygonCount,
                        'lines' => $lineCount,
                        'total_area_km2' => $totalArea,
                        'total_length_km' => $totalLength,
                        'total_points' => $validated['metadata']['totalPoints'],
                    ]
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            Log::error('Error saving shape collection: ' . $th->getMessage(), [
                'user_id' => $request->user()->id,
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save shape collection',
                'error' => config('app.debug') ? $th->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
