<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Status;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get application status code (lowercase) for conditional rendering
        $applicationStatus = $this->application?->status?->code ?? 'pending';

        return [
            "vehicle_id" => $this->id,
            "vehicle_info" => $this->vehicle_info,
            "plate_number" => $this->plate_number,
            "gate_pass_number" => $this->assigned_gate_pass ?? 'Not yet assigned',
            "registration_date" => $this->created_at->format("F d, Y"),
            "application_status" => $applicationStatus,
        ];
    }
}
