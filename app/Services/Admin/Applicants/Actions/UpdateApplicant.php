<?php

namespace App\Services\Admin\Applicants\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateApplicant
{
    public function handle(array $payload, string $context = "default")
    {
        $created = null;
        try {
            $created = DB::transaction(function () use ($payload, $context) {
                $user = User::with("details")->findOrFail($payload["id"]);

                if ($context === "address") {
                    $user->details()->update([
                        "region" => $payload["selectedRegion"],
                        "province" => $payload["selectedProvince"],
                        "municipality" => $payload["selectedMunicipality"],
                        "barangay" => $payload["selectedBarangay"],
                        "zip_code" => $payload["zip_code"],
                    ]);
                } else {
                    $user->update([
                        "email" => $payload["email"],
                    ]);

                    $user->details()->update([
                        "first_name" => $payload["first_name"],
                        "middle_name" => $payload["middle_name"] ?? null,
                        "last_name" => $payload["last_name"],
                        "suffix" => $payload["suffix"] ?? null,
                        "phone_number" => $payload["phone_number"],
                        "license_number" => $payload["license_number"] ?? null,
                    ]);
                }

                $user->refresh();
                $user->load("details");

                return $user;
            });
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
