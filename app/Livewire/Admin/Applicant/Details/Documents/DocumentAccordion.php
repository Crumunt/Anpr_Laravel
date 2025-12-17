<?php

namespace App\Livewire\Admin\Applicant\Details\Documents;

use App\Http\Resources\ApplicantDocumentResource;
use App\Models\Application;
use App\Models\Documents;
use App\Services\Admin\Applicants\ApplicantReadService;
use Exception;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class DocumentAccordion extends Component
{
    public int $index;
    public string $application_id = '';
    public array $application = [];
    public array $documents = [];

    public function mount()
    {
        $this->application = $this->fetchDocuments();
    }

    public function fetchDocuments()
    {

        $service = $this->fetchReadService();

        try {
            $application = $service->fetchApplication($this->application_id);

            return (new ApplicantDocumentResource($application))->resolve();
        } catch (Exception $e) {
            return;
        }
    }

    private function fetchReadService()
    {
        return app(ApplicantReadService::class);
    }


    private function createApplicationNumber()
    {
        $prefix = "APP";
        $formatted_date = date_format($this->application['created_at'], "Y");
        $application_id = $this->application_id;
        $formatted_identifier = substr($application_id, -8);

        return "$prefix-$formatted_date-$formatted_identifier";
    }

    private function formatLabel(string $key)
    {
        return Str::of($key)->headline()->value;
    }

    #[On('documentUpdated')]
    public function documentUpdated($applicationId)
    {
        $this->application_id = $applicationId;
        $this->mount();
        //do something
    }

    public function render()
    {
        return view('livewire.admin.applicant.details.documents.document-accordion');
    }
}
