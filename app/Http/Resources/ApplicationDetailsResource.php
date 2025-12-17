<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationDetailsResource extends JsonResource
{

    public function __construct($resource) {
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "application_id" => "APP-{$this->id}",
            "applicant_type" => ucfirst($this->applicant_type->value),
            "status" => $this->status->status_name,
            // expiry calculation to be added
            "expiry_date" => $this->created_at->format("F d, Y"),
        ];
    }
}
