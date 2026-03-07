<?php

namespace App\Http\Resources;

use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantResource extends JsonResource
{
    protected $context;

    public function __construct($resource, $context = "list")
    {
        parent::__construct($resource);
        $this->context = $context;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "date_created" => $this->created_at->format("F d, Y"),
            "archived_date" => $this->deleted_at ? $this->deleted_at->format("F d, Y") : null,

            ...$this->relationLoaded("details") && $this->context !== "detail"
                ? [
                    "name" => $this->details?->full_name,
                    "clsu_id" => $this->details?->clsu_id ?? 'applicant-' . substr($this->id, 0, 8),
                    "phone_number" => $this->phone_number,
                ]
                : [],

            ...$this->relationLoaded("applications")
                ? $this->getApplicationsData()
                : [],

            ...$this->context === "detail" ? $this->getDetailData() : [],
        ];
    }

    private function getApplicationsData()
    {
        $data = [];
        $applicant_type = $this->applications->first()?->applicantTypeModel?->label ?? 'Unknown';

        $data["applicant_type"] = [
            "badge_label" => $applicant_type,
        ];

        $data["applications"] = [
            "overview" => str_pad(
                $this->applications_count,
                2,
                "0",
                STR_PAD_LEFT,
            ),
            "tooltip" => [
                "active" => $this->active_applications_count ?? 0,
                "pending" => $this->pending_applications_count ?? 0,
                "rejected" => $this->rejected_applications_count ?? 0,
            ],
        ];

        return $data;
    }

    private function getDetailData()
    {
        $data = [];

        if ($this->relationLoaded("vehicles")) {
            $data["vehicle_details"] = VehicleResource::collection(
                $this->vehicles,
            )->resolve();
            $data[
                "application_details"
            ] = ApplicationDetailsResource::collection(
                $this->applications,
            )->resolve();
        }

        // Details
        if ($this->relationLoaded("details")) {
            $details = $this->getDetailedUserInfo();

            $data["personal_information"] = $details["personal_information"];
            $data["address_information"] = $details["address_information"];
        }

        if ($this->relationLoaded("documents")) {
            $data["documents"] = ApplicantDocumentResource::collection(
                $this->applications,
            )->resolve();
        }

        return $data;
    }

    private function getDetailedUserInfo()
    {
        $active_status = $this->is_active ? "Active" : "Inactive";
        $data = [];

        $data["personal_information"] = [
            "id" => $this->id,
            "name_initials" => $this->name_initial,
            "full_name" => $this->details?->full_name,
            "first_name" => $this->details?->first_name,
            "middle_name" => $this->details?->middle_name ?? "",
            "last_name" => $this->details?->last_name,
            "suffix" => $this->details?->suffix ?? "",
            "email" => $this->email,
            "clsu_id" => $this->details?->clsu_id ?? "",
            "active_status" => $active_status,
            "badge_class" => ApplicationDisplayHelper::renderBadgeClass(
                $active_status,
            ),
            "phone_number" => $this->details?->phone_number ?? "",
            "license_number" => $this->details?->license_number ?? "",
            "applicant_type" => $this->applications->first()?->applicantTypeModel?->label,
        ];

        $data["address_information"] = [
            "id" => $this->id,
            "region" => $this->details?->region_name,
            "city_municipality" => $this->details?->municipality,
            "province" => $this->details?->province,
            "barangay" => $this->details?->barangay,
            "zip_code" => $this->details?->zip_code,
        ];

        return $data;
    }

    public static function forList($resource)
    {
        return new self($resource, "list");
    }

    public static function forDetail($resource)
    {
        return new self($resource, "detail");
    }
}
