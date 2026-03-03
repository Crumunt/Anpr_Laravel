<?php

namespace App\Services\Admin\Applicants\Actions;

use App\Exceptions\ApplicantNotFoundException;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArchiveApplicant
{
    /**
     * Archive an applicant (soft delete)
     *
     * @param string $id The user ID to archive
     * @return User The archived user
     * @throws ApplicantNotFoundException
     */
    public function handle(string $id): User
    {
        try {
            $user = User::findOrFail($id);

            DB::transaction(function () use ($user) {
                $user->update([
                    'is_deleted' => true,
                    'deleted_at' => Carbon::now(),
                    'is_active' => false,
                ]);
            });

            // Log the archive activity
            ActivityLogService::logAccountArchived($user, auth()->user());

            Log::info('Applicant archived', [
                'applicant_id' => $id,
                'archived_by' => auth()->id(),
            ]);

            return $user;
        } catch (ModelNotFoundException $e) {
            Log::error("Applicant not found for archiving: {$id}");
            throw new ApplicantNotFoundException("Applicant not found");
        }
    }
}
