<?php

namespace App\Services\Admin\Applicants;

use App\Services\Admin\Applicants\Actions\CreateApplicant;
use App\Services\Admin\Applicants\Actions\DeleteApplicant;
use App\Services\Admin\Applicants\Actions\UpdateApplicant;

class ApplicantWriteService
{
    public function __construct(
        protected CreateApplicant $createApplicant,
        protected UpdateApplicant $updateApplicant,
        protected DeleteApplicant $deleteApplicant,
    ) {}

    public function create(array $payload, array $uploadedTempPaths = [])
    {
        return $this->createApplicant->handle($payload, $uploadedTempPaths);
    }

    public function update(array $payload, string $context = "default")
    {
        return $this->updateApplicant->handle($payload, $context);
    }

    public function delete($applicantID)
    {
        return $this->deleteApplicant->handle($applicantID);
    }
}
