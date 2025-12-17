<?php

namespace App\Services\Admin\Applicants\Actions;

use App\Exceptions\ApplicantNotFoundException;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Storage;

class DeleteApplicant
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function handle($id)
    {
        try {
            $user = User::findOrFail($id);

            $folder_path = "application/{$user->id}";

            DB::transaction(function () use ($user) {
                $user->delete();
            });

            Storage::disk("local")->deleteDirectory($folder_path);
        } catch (ModelNotFoundException $e) {
            Log::error("Applicant not found: {$id}");

            throw new ApplicantNotFoundException("Applicant not found");
        }
    }
}
