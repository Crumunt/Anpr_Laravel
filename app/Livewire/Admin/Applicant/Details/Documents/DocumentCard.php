<?php

namespace App\Livewire\Admin\Applicant\Details\Documents;

use App\Http\Resources\ApplicantDocumentResource;
use App\Models\User;
use App\Services\Admin\Applicants\ApplicantReadService;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;
use Ramsey\Collection\Collection;

class DocumentCard extends Component
{

    public string $user_id;
    public $applications = [];

    public function mount() {
        $this->applications = $this->fetchDocuments();
    }

    #[On('documentUpdated')]
    public function refreshDocuments()
    {
        $this->applications = $this->fetchDocuments();
    }

    public function fetchDocuments()
    {
        $user = User::with(['applications', 'documents'])->findOrFail($this->user_id);
        $applications = $user->applications;
        return ApplicantDocumentResource::collection($applications)->resolve();
    }

    private function fetchReadService()
    {
        return app(ApplicantReadService::class);
    }


    public function render()
    {
        return view('livewire.admin.applicant.details.documents.document-card');
    }
}
