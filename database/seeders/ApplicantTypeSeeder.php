<?php

namespace Database\Seeders;

use App\Models\ApplicantType;
use App\Models\ApplicantTypeDocument;
use Illuminate\Database\Seeder;

class ApplicantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define default applicant types with their required documents
        $applicantTypes = [
            [
                'name' => 'student',
                'label' => 'Student',
                'description' => 'Currently enrolled students of CLSU',
                'requires_clsu_id' => true,
                'requires_department' => true,
                'requires_position' => false,
                'is_active' => true,
                'sort_order' => 1,
                'documents' => [
                    [
                        'name' => 'vehicle_registration',
                        'label' => 'Vehicle Registration (OR/CR)',
                        'description' => 'Official Receipt and Certificate of Registration of the vehicle',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'license',
                        'label' => "Driver's License",
                        'description' => 'Valid driver\'s license',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'proof_of_identity',
                        'label' => 'Proof of Identification',
                        'description' => 'Valid CLSU ID, National ID, or Passport',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'certificate_of_registration',
                        'label' => 'Certificate of Registration (COR)',
                        'description' => 'Current semester certificate of registration',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'name' => 'faculty',
                'label' => 'Faculty',
                'description' => 'Faculty members of CLSU',
                'requires_clsu_id' => true,
                'requires_department' => true,
                'requires_position' => true,
                'is_active' => true,
                'sort_order' => 2,
                'documents' => [
                    [
                        'name' => 'vehicle_registration',
                        'label' => 'Vehicle Registration (OR/CR)',
                        'description' => 'Official Receipt and Certificate of Registration of the vehicle',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'license',
                        'label' => "Driver's License",
                        'description' => 'Valid driver\'s license',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'proof_of_identity',
                        'label' => 'Proof of Identification',
                        'description' => 'Valid CLSU ID, Government-issued ID, or Faculty ID',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'certificate_of_employment',
                        'label' => 'Certificate of Employment',
                        'description' => 'Certificate of employment or appointment',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'name' => 'staff',
                'label' => 'Staff',
                'description' => 'Staff members of CLSU',
                'requires_clsu_id' => true,
                'requires_department' => true,
                'requires_position' => true,
                'is_active' => true,
                'sort_order' => 3,
                'documents' => [
                    [
                        'name' => 'vehicle_registration',
                        'label' => 'Vehicle Registration (OR/CR)',
                        'description' => 'Official Receipt and Certificate of Registration of the vehicle',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'license',
                        'label' => "Driver's License",
                        'description' => 'Valid driver\'s license',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'proof_of_identity',
                        'label' => 'Proof of Identification',
                        'description' => 'Valid CLSU ID, Government-issued ID, or Staff ID',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'certificate_of_employment',
                        'label' => 'Certificate of Employment',
                        'description' => 'Certificate of employment or appointment',
                        'accepted_formats' => 'pdf,jpg,jpeg,png',
                        'max_file_size' => 10240,
                        'is_required' => true,
                        'sort_order' => 4,
                    ],
                ],
            ],
        ];

        foreach ($applicantTypes as $typeData) {
            $documents = $typeData['documents'] ?? [];
            unset($typeData['documents']);

            // Create or update the applicant type
            $applicantType = ApplicantType::updateOrCreate(
                ['name' => $typeData['name']],
                $typeData
            );

            // Create documents for this type
            foreach ($documents as $documentData) {
                ApplicantTypeDocument::updateOrCreate(
                    [
                        'applicant_type_id' => $applicantType->id,
                        'name' => $documentData['name'],
                    ],
                    $documentData
                );
            }
        }
    }
}
