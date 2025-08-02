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
            // General workflow
            ['code' => 'pending', 'status_name' => 'Pending', 'description' => 'Awaiting review or approval.'],
            ['code' => 'under_review', 'status_name' => 'Under Review', 'description' => 'Currently being checked by the admin.'],
            ['code' => 'approved', 'status_name' => 'Approved', 'description' => 'Approved and allowed to proceed.'],
            ['code' => 'rejected', 'status_name' => 'Rejected', 'description' => 'Rejected due to invalid or incomplete requirements.'],
            ['code' => 'active', 'status_name' => 'Active', 'description' => 'Currently active in the system.'],
            ['code' => 'inactive', 'status_name' => 'Inactive', 'description' => 'Temporarily disabled.'],
            ['code' => 'expired', 'status_name' => 'Expired', 'description' => 'Validity period has expired.'],
            ['code' => 'revoked', 'status_name' => 'Revoked', 'description' => 'Previously approved but access has been revoked.'],

            // Security / enforcement
            ['code' => 'blacklisted', 'status_name' => 'Blacklisted', 'description' => 'Banned from entering the premises.'],
            ['code' => 'flagged', 'status_name' => 'Flagged', 'description' => 'Requires manual inspection before entry.'],

            // Asset tracking
            ['code' => 'lost', 'status_name' => 'Lost', 'description' => 'RFID tag or plate reported lost.'],
            ['code' => 'stolen', 'status_name' => 'Stolen', 'description' => 'Vehicle reported stolen.'],

            // System
            ['code' => 'maintenance', 'status_name' => 'Maintenance', 'description' => 'System, gate, or camera under maintenance.'],
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['code' => $status['code']],
                [
                    'status_name' => $status['status_name'],
                    'description' => $status['description'],
                ]
            );
        }
    }
}
