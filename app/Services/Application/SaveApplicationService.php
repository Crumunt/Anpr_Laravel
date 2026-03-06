<?php

namespace App\Services\Application;

use App\Models\Documents;
use App\Models\Status;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\UserInvitationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveApplicationService
{
    public function __construct(
        protected UserInvitationService $invitationService
    ) {}

    public function handle(array $payload, array $uploadedTempPaths = []): array
    {
        $created = null;
        try {
            $created = DB::transaction(function () use (
                $payload,
                $uploadedTempPaths,
            ) {
                $application_status = Status::applicationPending();
                $vehicle_status = Status::vehiclePending();

                $applicant = User::create([
                    "email" => $payload["email"],
                    "password" => Str::random(10),
                    "must_change_password" => true,
                ]);

                $applicant->details()->create([
                    "first_name" => $payload["first_name"],
                    "middle_name" => $payload["middle_name"] ?? null,
                    "last_name" => $payload["last_name"],
                    "suffix" => $payload["suffix"] ?? null,
                    "region" => $payload["selectedRegion"],
                    "province" => $payload["selectedProvince"],
                    "municipality" => $payload["selectedMunicipality"],
                    "barangay" => $payload["selectedBarangay"],
                    "zip_code" => $payload["zip_code"],
                    "phone_number" => $payload["phone"],
                    "clsu_id" => $payload["clsu_id"] ?? null,
                    "college_unit_department" => $payload["department"] ?? null,
                    "position" => $payload["position"] ?? null,
                ]);

                $application = $applicant->applications()->create([
                    "license_number" => $payload["license_number"] ?? null,
                    "applicant_type_id" => $payload["applicant_type"],
                    "status_id" => $application_status->id,
                ]);

                $applicant->vehicles()->create([
                    "plate_number" => $payload["plate_number"],
                    "type" => $payload["vehicle_type"] ?? "test",
                    "make" => $payload["make"],
                    "model" => $payload["model"],
                    "year" => $payload["year"],
                    "color" => $payload["color"],
                    "status_id" => $vehicle_status->id,
                ]);

                $file_records = [];
                foreach ($uploadedTempPaths as $tmp_file) {
                    $filename = Str::uuid() . '.' . $tmp_file['tmp_path']->getClientOriginalExtension();

                    $store_path = "application/{$applicant->id}/{$application->id}";
                    $final_path = "{$store_path}/" . $filename;
                    $tmp_file['tmp_path']->storeAs($store_path, $filename, 'local');

                    $file_records[] = [
                        "type" => $tmp_file["type"],
                        "file_path" => $final_path,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];
                }

                if (!empty($fileRecords)) {
                    $application->documents()->createMany($fileRecords);
                }

                return $applicant;
            });
        } catch (\Throwable $e) {
            throw $e;
        }

        $emailSent = false;

        if ($created) {
            $created->assignRole("applicant");

            // Log application submission
            ActivityLogService::logApplicationSubmitted($created, $payload['applicant_type'] ?? 'Unknown');

            // Send invitation email
            try {
                $emailSent = $this->invitationService->sendWelcomeEmail($created);

                Log::info('Applicant invitation email sent', [
                    'user_id' => $created->id,
                    'email' => $created->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send applicant invitation email', [
                    'user_id' => $created->id,
                    'email' => $created->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'user' => $created,
            'emailSent' => $emailSent,
        ];
    }
}
