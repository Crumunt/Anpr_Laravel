<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ApplicantDocumentResource extends JsonResource
{
    public function __construct($resource)
    {
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
            "application_number" => $this->createApplicationNumber(),
            "applicant_type" => ucfirst($this->user->getRoleNames()->first()),
            "status" => $this->status->code,
            "date" => date_format($this->created_at, "M d, Y"),

            "documents" => $this->user->documents->map(function ($doc) {
                return [
                    'document_id' => $doc['id'],
                    "label" => $this->formatLabel($doc["type"]),
                    "uploaded" => date_format($doc["created_at"], "M d, Y"),
                    "url" => route('documents.view', $doc['id']),
                    "status" => $doc['status']['code'],
                    'mime_type' => $doc['mime_type'],
                    "bg" => $doc["bgColor"] ?? "green",
                    "icon" => $doc["iconColor"] ?? "green",
                ];
            }),
        ];
    }

    private function createApplicationNumber()
    {
        $prefix = "APP";
        $formatted_date = date_format($this->created_at, "Y");
        $application_id = $this->id;
        $formatted_identifier = substr($application_id, -8);

        return "$prefix-$formatted_date-$formatted_identifier";
    }

    private function formatLabel(string $key)
    {
        return Str::of($key)->headline()->value;
    }
}
