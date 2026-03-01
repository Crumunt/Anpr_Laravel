<?php

namespace App\Services\Admin\Applicants;

use App\Models\Application;
use App\Models\Status;
use App\Models\User;
use App\Models\Vehicle\Vehicle;
use App\Services\Admin\Applicants\Actions\CreateApplicant;
use App\Services\Admin\Applicants\Actions\DeleteApplicant;
use App\Services\Admin\Applicants\Actions\UpdateApplicant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicantWriteService
{
    public function __construct(
        protected CreateApplicant $createApplicant,
        protected UpdateApplicant $updateApplicant,
        protected DeleteApplicant $deleteApplicant,
    ) {}

    public function create(array $payload, array $uploadedTempPaths = [], bool $sendInvitation = true): array
    {
        return $this->createApplicant->handle($payload, $uploadedTempPaths, $sendInvitation);
    }

    public function update(array $payload, string $context = "default")
    {
        return $this->updateApplicant->handle($payload, $context);
    }

    public function delete($applicantID)
    {
        return $this->deleteApplicant->handle($applicantID);
    }

    /**
     * Bulk delete multiple applicants
     *
     * @param array $applicantIds Array of user IDs to delete
     * @return array Result with success count and failed IDs
     */
    public function bulkDelete(array $applicantIds): array
    {
        $successCount = 0;
        $failedIds = [];

        DB::beginTransaction();

        try {
            foreach ($applicantIds as $applicantId) {
                try {
                    $this->deleteApplicant->handle($applicantId);
                    $successCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to delete applicant in bulk operation', [
                        'applicant_id' => $applicantId,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $applicantId;
                }
            }

            DB::commit();

            Log::info('Bulk applicant deletion completed', [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($applicantIds)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk applicant deletion failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Bulk approve multiple applicants
     *
     * @param array $applicantIds Array of user IDs to approve
     * @return array Result with success count and failed IDs
     */
    public function bulkApprove(array $applicantIds): array
    {
        return $this->bulkUpdateApplicationStatus($applicantIds, 'approved');
    }

    /**
     * Bulk reject multiple applicants
     *
     * @param array $applicantIds Array of user IDs to reject
     * @return array Result with success count and failed IDs
     */
    public function bulkReject(array $applicantIds): array
    {
        return $this->bulkUpdateApplicationStatus($applicantIds, 'rejected');
    }

    /**
     * Bulk set applications to under review
     *
     * @param array $applicantIds Array of user IDs
     * @return array Result with success count and failed IDs
     */
    public function bulkSetUnderReview(array $applicantIds): array
    {
        return $this->bulkUpdateApplicationStatus($applicantIds, 'under_review');
    }

    /**
     * Internal method to bulk update application status
     */
    private function bulkUpdateApplicationStatus(array $applicantIds, string $statusCode): array
    {
        $successCount = 0;
        $failedIds = [];

        $status = Status::where('type', 'application')
            ->where('code', $statusCode)
            ->first();

        if (!$status) {
            Log::error('Status not found for bulk operation', ['status_code' => $statusCode]);
            return [
                'success_count' => 0,
                'failed_ids' => $applicantIds,
                'total' => count($applicantIds),
                'error' => 'Status not found'
            ];
        }

        DB::beginTransaction();

        try {
            // Get the corresponding vehicle status
            $vehicleStatusCode = match($statusCode) {
                'approved' => 'active',
                'rejected' => 'inactive',
                default => 'pending_verification'
            };
            $vehicleStatus = Status::where('type', 'vehicle')
                ->where('code', $vehicleStatusCode)
                ->first();

            foreach ($applicantIds as $applicantId) {
                try {
                    $user = User::with(['applications', 'vehicles'])->find($applicantId);

                    if (!$user) {
                        $failedIds[] = $applicantId;
                        continue;
                    }

                    // Update all applications for this user
                    $updated = $user->applications()->update([
                        'status_id' => $status->id,
                        'approved_by' => $statusCode === 'approved' ? Auth::id() : null
                    ]);

                    if ($updated > 0) {
                        // Also update user active status based on application status
                        if ($statusCode === 'approved') {
                            $user->is_active = true;
                            $user->save();
                        } elseif ($statusCode === 'rejected') {
                            $user->is_active = false;
                            $user->save();
                        }

                        // Sync vehicle status with application status
                        if ($vehicleStatus) {
                            if ($statusCode === 'approved') {
                                // Set expiration dates for approved vehicles
                                $approvalDate = now();
                                $defaultValidityYears = config('anpr.gate_pass.default_validity_years', 4);
                                $inactiveStatus = Status::where('type', 'vehicle')->where('code', 'inactive')->first();

                                $vehicles = $user->vehicles;
                                foreach ($vehicles as $vehicle) {
                                    $vehicle->status_id = $vehicleStatus->id;
                                    $vehicle->setExpirationFromDate($approvalDate, $defaultValidityYears);
                                    $vehicle->save();

                                    // Handle renewals: update original vehicle
                                    if ($vehicle->is_renewal && $vehicle->renewed_from_vehicle_id) {
                                        $originalVehicle = Vehicle::find($vehicle->renewed_from_vehicle_id);
                                        if ($originalVehicle) {
                                            // Inherit gate pass number
                                            if (!$vehicle->assigned_gate_pass && $originalVehicle->assigned_gate_pass) {
                                                $vehicle->assigned_gate_pass = $originalVehicle->assigned_gate_pass;
                                                $vehicle->save();
                                            }
                                            // Mark original as inactive
                                            if ($inactiveStatus) {
                                                $originalVehicle->status_id = $inactiveStatus->id;
                                                $originalVehicle->save();
                                            }
                                        }
                                    }
                                }
                            } else {
                                $user->vehicles()->update([
                                    'status_id' => $vehicleStatus->id
                                ]);
                            }
                        }

                        $successCount++;
                    } else {
                        $failedIds[] = $applicantId;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to update applicant status in bulk operation', [
                        'applicant_id' => $applicantId,
                        'status_code' => $statusCode,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $applicantId;
                }
            }

            DB::commit();

            Log::info("Bulk applicant {$statusCode} completed", [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($applicantIds)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk applicant status update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Bulk activate multiple applicant accounts
     *
     * @param array $applicantIds Array of user IDs to activate
     * @return array Result with success count and failed IDs
     */
    public function bulkActivate(array $applicantIds): array
    {
        return $this->bulkUpdateAccountStatus($applicantIds, true);
    }

    /**
     * Bulk deactivate multiple applicant accounts
     *
     * @param array $applicantIds Array of user IDs to deactivate
     * @return array Result with success count and failed IDs
     */
    public function bulkDeactivate(array $applicantIds): array
    {
        return $this->bulkUpdateAccountStatus($applicantIds, false);
    }

    /**
     * Internal method to bulk update account activation status
     */
    private function bulkUpdateAccountStatus(array $applicantIds, bool $isActive): array
    {
        $successCount = 0;
        $failedIds = [];

        DB::beginTransaction();

        try {
            foreach ($applicantIds as $applicantId) {
                try {
                    $user = User::find($applicantId);

                    if (!$user) {
                        $failedIds[] = $applicantId;
                        continue;
                    }

                    $user->is_active = $isActive;
                    $user->save();
                    $successCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to update applicant account status in bulk operation', [
                        'applicant_id' => $applicantId,
                        'is_active' => $isActive,
                        'error' => $e->getMessage()
                    ]);
                    $failedIds[] = $applicantId;
                }
            }

            DB::commit();

            $action = $isActive ? 'activation' : 'deactivation';
            Log::info("Bulk applicant account {$action} completed", [
                'success_count' => $successCount,
                'failed_count' => count($failedIds)
            ]);

            return [
                'success_count' => $successCount,
                'failed_ids' => $failedIds,
                'total' => count($applicantIds)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk applicant account status update failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
