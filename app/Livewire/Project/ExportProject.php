<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;

class ExportProject extends Component
{
    public Project $project;
    public $showModal = false;

    protected $listeners = ['openExportModal' => 'open'];

    public function open() { $this->showModal = true; }

    public function render()
    {
        return view('livewire.project.export-project', [
            'steps' => $this->project->steps()->orderBy('order')->get()
        ]);
    }
}