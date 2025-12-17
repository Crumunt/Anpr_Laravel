<?php

namespace App\Livewire\Admin\Applicant\Details;

use Livewire\Component;

class InfoTable extends Component
{
    public string $cardTitle = "";
    public array $headers = [];
    public array $rows = [];
    public bool $canCreate = false;

    public $application;

    public function approveApplication()
    {
        // $previousStatus = $this->application->status ?? null;

        try {
            // Your approval logic here
            // $this->application->update(['status' => 'approved']);

            // Dispatch toast event
            $this->dispatch(
                "toast",
                message: "Application approved successfully",
                type: "success",
                duration: 5000,
            );

            $this->dispatch('close-dropdown');
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to approve application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function rejectApplication()
    {
        try {
            // Your rejection logic here
            // $this->application->update(['status' => 'rejected']);

            $this->dispatch(
                "toast",
                message: "Application rejected",
                type: "warning",
                duration: 5000,
            );
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to reject application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function deleteApplication()
    {
        try {
            // Your delete logic here
            // $this->application->delete();

            $this->dispatch(
                "toast",
                message: "Application deleted successfully",
                type: "success",
            );

            // Optionally redirect
            // return redirect()->route('applications.index');
        } catch (\Exception $e) {
            $this->dispatch(
                "toast",
                message: "Failed to delete application: " . $e->getMessage(),
                type: "error",
            );
        }
    }

    public function approve($model) {

    }

    public function render()
    {
        return view("livewire.admin.applicant.details.info-table");
    }
}
