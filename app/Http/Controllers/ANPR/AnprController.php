<?php

namespace App\Http\Controllers\ANPR;

use App\Http\Controllers\Controller;
use App\Models\ANPR\Gate;
use App\Models\ANPR\Record;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnprController extends Controller
{
    //
    public function webhook(Request $request)
    {
        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'timestamp' => 'required|string',
            'camera_id' => 'nullable|string',
            'gate_type' => 'nullable|string',
            'location' => 'nullable|string',
            'detections' => 'required|array',
            'detections.*.plate_text' => 'required|string',
            'detections.*.confidence' => 'required|numeric',
            'detections.*.bbox' => 'nullable|array',
            'detections.*.bbox.x1' => 'nullable|numeric',
            'detections.*.bbox.y1' => 'nullable|numeric',
            'detections.*.bbox.x2' => 'nullable|numeric',
            'detections.*.bbox.y2' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            Log::error('ALPR webhook validation failed', [
                'errors' => $validator->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            // Process each detection
            $savedDetections = [];
            $skippedDuplicates = [];

            foreach ($validated['detections'] as $detection) {
                // Skip unreadable plates
                if ($detection['plate_text'] === 'UNREADABLE') {
                    continue;
                }

                $plateNumber = $detection['plate_text'];

                // Check for duplicate detection in the last 5 minutes to avoid duplicates
                // (e.g., when a vehicle is stopped at the gate for questioning)
                // Only consider it a duplicate if same plate, location, and gate_type
                $recentRecord = Record::where('plate_number', $plateNumber)
                    ->where('location', $validated['location'] ?? null)
                    ->where('gate_type', $validated['gate_type'] ?? null)
                    ->where('detected_at', '>=', now()->subMinutes(5))
                    ->first();

                if ($recentRecord) {
                    $skippedDuplicates[] = $plateNumber;
                    continue;
                }

                // Check if this plate has a registered vehicle with a gate pass
                $registeredVehicle = Vehicle::where('plate_number', $plateNumber)
                    ->whereNotNull('assigned_gate_pass')
                    ->where('assigned_gate_pass', '!=', '')
                    ->first();

                // Look up the gate by gate_name and gate_location to get gate_id
                $gateName = $validated['gate_type'] ?? null;
                $gateLocation = $validated['location'] ?? null;
                $gate = ($gateName && $gateLocation)
                    ? Gate::where('gate_name', $gateName)->where('gate_location', $gateLocation)->first()
                    : null;

                // Create detection record
                $plateDetection = Record::create([
                    'plate_number' => $plateNumber,
                    'confidence' => $detection['confidence'],
                    'bbox_x1' => $detection['bbox']['x1'] ?? null,
                    'bbox_y1' => $detection['bbox']['y1'] ?? null,
                    'bbox_x2' => $detection['bbox']['x2'] ?? null,
                    'bbox_y2' => $detection['bbox']['y2'] ?? null,
                    'camera_id' => $validated['camera_id'] ?? null,
                    'gate_type' => $gate?->slug ?? $gateName,
                    'gate_id' => $gate?->id,
                    'location' => $gateLocation,
                    'detected_at' => $validated['timestamp'] ?? now(),
                    'gate_pass_number' => $registeredVehicle?->assigned_gate_pass,
                    'vehicle_id' => $registeredVehicle?->id,
                ]);

                $savedDetections[] = $plateDetection->id;
            }

            Log::info('ALPR webhook processed successfully', [
                'saved_count' => count($savedDetections),
                'detection_ids' => $savedDetections,
                'skipped_duplicates_count' => count($skippedDuplicates),
                'skipped_plates' => $skippedDuplicates
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Detections received and saved',
                'saved_count' => count($savedDetections),
                'detection_ids' => $savedDetections,
                'skipped_duplicates' => count($skippedDuplicates)
            ], 200);

        } catch (\Exception $e) {
            Log::error('ALPR webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing detections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all plate detections
     *
     * Route: GET /api/alpr/detections
     */
    public function index(Request $request)
    {
        // $perPage = $request->input('per_page', 15);

        // $detections = PlateDetection::orderBy('detected_at', 'desc')
        //     ->paginate($perPage);

        // return response()->json($detections);
    }

    /**
     * Get a specific plate detection
     *
     * Route: GET /api/alpr/detections/{id}
     */
    // public function show($id)
    // {
    //     $detection = PlateDetection::findOrFail($id);

    //     return response()->json($detection);
    // }

    /**&gate_type={type}&camera_id={id}
     */
    // public function search(Request $request)
    // {
    //     $plateNumber = $request->input('plate');

    //     if (!$plateNumber) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Plate number required'
    //         ], 400);
    //     }

    //     $query = PlateDetection::where('plate_number', 'LIKE', "%{$plateNumber}%");

    //     // Filter by gate type if provided
    //     if ($request->has('gate_type')) {
    //         $query->where('gate_type', $request->input('gate_type'));
    //     }

    //     // Filter by camera ID if provided
    //     if ($request->has('camera_id')) {
    //         $query->where('camera_id', $request->input('camera_id'));
    //     }

    //     $detections = $query->orderBy('detected_at', 'desc')->get();

    //     return response()->json([
    //         'success' => true,
    //         'plate_number' => $plateNumber,
    //         'total_found' => $detections->count(),
    //         'detections' => $detections
    //     ]);
    // }

    /**
     * Get detections by gate type
     *
     * Route: GET /api/alpr/gate/{type}
     */
    // public function byGate($gateType)
    // {
    //     $detections = PlateDetection::byGateType($gateType)
    //         ->orderBy('detected_at', 'desc')
    //         ->paginate(15);

    //     return response()->json($detections);
    // }

    /**
     * Match entrance and exit for a plate
     *
     * Route: GET /api/alpr/match/{plate}
     */
    // public function matchPlate($plateNumber)
    // {
    //     $entrance = PlateDetection::where('plate_number', $plateNumber)
    //         ->entrance()
    //         ->orderBy('detected_at', 'desc')
    //         ->first();

    //     $exit = PlateDetection::where('plate_number', $plateNumber)
    //         ->exit()
    //         ->orderBy('detected_at', 'desc')
    //         ->first();

    //     $duration = null;
    //     if ($entrance && $exit) {
    //         $duration = $entrance->detected_at->diffInMinutes($exit->detected_at);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'plate_number' => $plateNumber,
    //         'entrance' => $entrance,
    //         'exit' => $exit,
    //         'duration_minutes' => $duration,
    //         'plate_number' => $plateNumber,
    //         'total_found' => $detections->count(),
    //         'detections' => $detections
    //     ]);
    // }
}
