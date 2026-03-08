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
            "application_id" => $this->id,
            "application_number" => $this->createApplicationNumber(),
            "applicant_type" => ucfirst($this->user->getRoleNames()->first()),
            "status" => $this->status->code,
            "created_at" => $this->created_at,
            "date" => date_format($this->created_at, "M d, Y"),

            "documents" => $this->documents->map(function ($doc) {
                $label = $this->formatLabel($doc["type"]);

                // Add version indicator for resubmitted documents
                if ($doc['version'] > 1) {
                    $label .= $doc['is_current']
                        ? ' (v' . $doc['version'] . ' - Current)'
                        : ' (v' . $doc['version'] . ')';
                } elseif (!$doc['is_current'] && $doc['replaced_by']) {
                    // Original document that has been replaced
                    $label .= ' (Superseded)';
                }

                return [
                    'document_id' => $doc['id'],
                    "label" => $label,
                    "uploaded" => date_format($doc["created_at"], "M d, Y"),
                    "url" => route('documents.view', $doc['id']),
                    "status" => $doc['status']['code'],
                    'mime_type' => $doc['mime_type'],
                    "bg" => $doc["bgColor"] ?? "green",
                    "icon" => $doc["iconColor"] ?? "green",
                    "version" => $doc['version'] ?? 1,
                    "is_current" => $doc['is_current'] ?? true,
                    "is_resubmission" => ($doc['version'] ?? 1) > 1,
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
