<?php

namespace App\Services\Admin\Applicants\Actions;

use App\Models\Documents;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateApplicant
{
    public function handle(array $payload, array $uploadedTempPaths)
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
                    "clsu_id" => $payload["clsu_id"],
                    "college_unit_department" => $payload["department"],
                    "position" => $payload["position"] ?? null,
                ]);

                $application = $applicant->applications()->create([
                    "license_number" => $payload["license_number"] ?? null,
                    "applicant_type" => $payload["applicant_type"],
                    "status_id" => $application_status->id,
                ]);

                $applicant->vehicles()->create([
                    "application_id" => $application->id,
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
                    $filename = Str::uuid() . '.' . $tmp_file['file']->getClientOriginalExtension();

                    $store_path = "application/{$applicant->id}/{$application->id}";
                    $final_path = "{$store_path}/" . $filename;
                    $tmp_file['file']->storeAs($store_path, $filename, 'local');

                    $file_records[] = [
                        "type" => $tmp_file["type"],
                        "file_path" => $final_path,
                        'mime_type' => $tmp_file['mime_type'],
                        'file_size' => $tmp_file['file_size'],
                        'status_id' => $application_status->id,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];
                }

                if (!empty($file_records)) {
                    $application->documents()->createMany($file_records);
                }

                return $applicant;
            });
        } catch (\Throwable $e) {
            // TO ADD PROPER ERROR HANDLING IN-FUTURE-UPDATES
            throw $e;
        }

        if ($created) {
            $created->assignRole("applicant");
        }

        return $created;
    }
}
