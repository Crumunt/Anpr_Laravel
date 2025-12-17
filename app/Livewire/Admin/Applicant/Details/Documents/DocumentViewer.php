<?php

namespace App\Livewire\Admin\Applicant\Details\Documents;

use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Livewire\Component;

class DocumentViewer extends Component
{

    public ?array $currentDocument = null;
    public bool $show = false;

    #[On('open-document-viewer')]
    public function openViewer(array $document)
    {
        $this->currentDocument = $document;
        $this->show = true;
    }

    public function getIsImageProperty(): bool
    {
        if (!$this->currentDocument || !isset($this->currentDocument['mime_type'])) return false;

        $mime = strtolower($this->currentDocument['mime_type']);
        return str_contains($mime, 'jpg') || str_contains($mime, 'jpeg') ||
            str_contains($mime, 'png') || str_contains($mime, 'gif') ||
            str_contains($mime, 'webp') || str_contains($mime, 'bmp');
    }

    public function getIsPDFProperty(): bool
    {
        if (!$this->currentDocument || !isset($this->currentDocument['mime_type'])) return false;
        return str_contains(strtolower($this->currentDocument['mime_type']), 'pdf');
    }

    public function approveDocument()
    {
        // Your server-side logic here (database update, etc.)
        $docId = $this->currentDocument['id'];

        // Dispatch an event to update the parent list
        $this->dispatch('documentUpdated', id: $docId, status: 'approved');

        // Close and reset the component state
        $this->closeAndClear();
    }

    // ... other action methods (rejectDocument, markAsPending) ...

    public function closeAndClear()
    {
        // We close the modal first
        $this->show = false;

        // Give the component time to disappear visually (Alpine transition)
        // Then, clear the data to prevent caching issues.
        // We use $this->skipRender() here to prevent the component from re-rendering
        // with null data before the modal is hidden.
        // $this->skipRender();
        $this->dispatch('clear-cached-document');
    }

    public function resetRejectForm()
    { /* ... */
    }
    public function render()
    {
        return view('livewire.admin.applicant.details.documents.document-viewer');
    }
}
