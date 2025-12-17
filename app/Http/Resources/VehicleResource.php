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
        return [
            "vehicle_info" => $this->vehicle_info,
            "plate_number" => $this->plate_number,
            "gate_pass_number" => $this->gate_pass ?? 'Not yet assigned',
            "status" => $this->status->status_name,
            "registration_date" => $this->created_at->format("F d, Y"),
        ];
    }
}
