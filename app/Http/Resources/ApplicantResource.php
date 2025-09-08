<?php

namespace App\Http\Resources;

use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->details?->clsu_id ?? '-',
            'name' => ApplicationDisplayHelper::getFullNameAttribute(
                $this->first_name,
                $this->middle_name,
                $this->last_name
            ),
            'email' => $this->email,
            'phone_number' => ApplicationDisplayHelper::formatPhoneNumber($this->details?->phone_number),
            'status' => [
                'label' => ucfirst($this->details?->status?->status_name ?? 'Unknown')
            ],
            'submitted_date' => $this->created_at->format('F d, Y'),
            'vehicles' => $this->vehicles_count ?? $this->vehicles->count()
        ];
    }
}
