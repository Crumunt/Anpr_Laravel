<?php

namespace App\Services\Admin\Applicants\Actions;

use App\Exceptions\ApplicantNotFoundException;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestoreApplicant
{
    /**
     * Restore an archived applicant
     *
     * @param string $id The user ID to restore
     * @return User The restored user
     * @throws ApplicantNotFoundException
     */
    public function handle(string $id): User
    {
        try {
            $user = User::findOrFail($id);

            DB::transaction(function () use ($user) {
                $user->update([
                    'is_deleted' => false,
                    'deleted_at' => null,
                    'is_active' => true,
                ]);
            });

            // Log the restore activity
            ActivityLogService::logAccountRestored($user, auth()->user());

            Log::info('Applicant restored from archive', [
                'applicant_id' => $id,
                'restored_by' => auth()->id(),
            ]);

            return $user;
        } catch (ModelNotFoundException $e) {
            Log::error("Applicant not found for restoration: {$id}");
            throw new ApplicantNotFoundException("Applicant not found");
        }
    }
}
