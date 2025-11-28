<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            "application" => [
                [
                    "code" => "draft",
                    "status_name" => "Draft",
                    "description" =>
                        "Applicant started but hasn’t submitted yet",
                ],
                [
                    "code" => "submitted",
                    "status_name" => "Submitted",
                    "description" =>
                        "Application submitted, waiting for review",
                ],
                [
                    "code" => "under_review",
                    "status_name" => "Under Review",
                    "description" => "Admin reviewing the application",
                ],
                [
                    "code" => "approved",
                    "status_name" => "Approved",
                    "description" => "Application approved by admin",
                ],
                [
                    "code" => "rejected",
                    "status_name" => "Rejected",
                    "description" => "Application rejected by admin",
                ],
                [
                    "code" => "expired",
                    "status_name" => "Expired",
                    "description" => "Application expired (if time-limited)",
                ],
                [
                    "code" => "cancelled",
                    "status_name" => "Cancelled",
                    "description" => "Applicant cancelled the request",
                ],
                [
                    "code" => "needs_revision",
                    "status_name" => "Needs Revision",
                    "description" => "Admin requested changes from applicant",
                ],
            ],

            "vehicle" => [
                [
                    "code" => "active",
                    "status_name" => "Active",
                    "description" => "Vehicle approved and usable",
                ],
                [
                    "code" => "inactive",
                    "status_name" => "Inactive",
                    "description" =>
                        "Vehicle temporarily inactive or suspended",
                ],
                [
                    "code" => "pending_verification",
                    "status_name" => "Pending Verification",
                    "description" => "Vehicle registration awaiting approval",
                ],
                [
                    "code" => "blacklisted",
                    "status_name" => "Blacklisted",
                    "description" => "Vehicle flagged for repeated violations",
                ],
                [
                    "code" => "decommissioned",
                    "status_name" => "Decommissioned",
                    "description" => "Vehicle removed or permanently inactive",
                ],
            ],

            "anpr" => [
                [
                    "code" => "entered",
                    "status_name" => "Entered",
                    "description" => "Vehicle successfully entered",
                ],
                [
                    "code" => "exited",
                    "status_name" => "Exited",
                    "description" => "Vehicle successfully exited",
                ],
                [
                    "code" => "violation_flagged",
                    "status_name" => "Violation Flagged",
                    "description" => "Vehicle triggered a violation",
                ],
                [
                    "code" => "denied",
                    "status_name" => "Denied",
                    "description" => "Entry denied (unauthorized vehicle)",
                ],
                [
                    "code" => "pending",
                    "status_name" => "Pending",
                    "description" => "Entry attempted, pending manual review",
                ],
                [
                    "code" => "overstayed",
                    "status_name" => "Overstayed",
                    "description" => "Vehicle stayed beyond allowed time",
                ],
                [
                    "code" => "restricted_area",
                    "status_name" => "Restricted Area",
                    "description" =>
                        "Vehicle attempted entry into restricted area",
                ],
            ],
        ];

        foreach ($statuses as $type => $statusList) {
            foreach ($statusList as $status) {
                Status::updateOrCreate(
                    ["type" => $type, "code" => $status["code"]],
                    [
                        "status_name" => $status["status_name"],
                        "description" => $status["description"] ?? null,
                    ]
                );
            }
        }
    }
}
