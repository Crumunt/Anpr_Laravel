<?php

namespace App\Services\Admin\Vehicles\Actions;

use App\Models\Status;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateVehicle
{
    /**
     * Create a new class instance.
     */
    public function handle(array $payload = [], array $uploadedTempPaths = [])
    {
        $created = null;
        try {
            $user = User::findOrFail($payload['user_id'])->first();

            $created = DB::transaction(function () use ($user, $payload, $uploadedTempPaths) {
                $user_id = $user->id;
                $applicant_type = $user->applications()->first()->applicant_type;
                // Statuses
                $application_status = Status::applicationPending();
                $vehicle_status = Status::vehiclePending();

                $application = $user->applications()->create([
                    'user_id' => $user_id,
                    'applicant_type' => $applicant_type,
                    'status_id' => $application_status->id
                ]);

                $user->vehicles()->create([
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

                    $store_path = "application/{$user_id}/{$user_id}";
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
                };

                if (!empty($file_records)) {
                    $application->documents()->createMany($file_records);
                }

                return $application;
            });
        } catch (Exception $e) {
            throw $e;
        }


        return $created;
    }
}
