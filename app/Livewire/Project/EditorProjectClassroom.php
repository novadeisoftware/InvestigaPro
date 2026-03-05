<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\ProjectStep;
use App\Models\ClassroomStep;
use App\Models\ProjectComment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditorProjectClassroom extends Component
{
    public Project $project;
    public $currentStepId;
    public $content = ''; // Inicializamos como string vacío para evitar nulls
    public $lastSaved;

    public $isReadOnly = false;
    public $newComment = ''; 
    public $comments = []; 

    public function mount(Project $project, $stepId = null)
    {
        $this->project = $project->load(['classroom.classroomSteps', 'university']);
        
        // Determinamos el modo (Alumno o Asesor)
        $this->isReadOnly = (request()->query('mode') === 'advisor');

        // Seleccionamos el paso inicial
        $targetStepId = $stepId ?? $this->project->steps()->orderBy('order', 'asc')->first()?->id;
        
        if ($targetStepId) {
            $this->selectStep($targetStepId);
        }
    }

    public function isLocked($step)
    {
        if ($this->isReadOnly) return false;

        $cStep = ClassroomStep::find($step->classroom_step_id);
        if (!$cStep) return false;
    
        if ($cStep->availability_mode === 'locked') return true;
    
        if ($cStep->availability_mode === 'scheduled' && $cStep->available_at && $cStep->available_at->isFuture()) {
            return true;
        }
    
        return false;
    }

    public function selectStep($stepId)
    {
        // Antes de cambiar, guardamos el progreso del paso anterior (si no es modo lectura)
        if ($this->currentStepId && !$this->isReadOnly) { 
            $this->saveProgress(); 
        }

        $step = ProjectStep::find($stepId); 
        
        if (!$this->isReadOnly && $this->isLocked($step)) {
            $this->dispatch('swal', [
                'title' => 'Acceso Restringido',
                'text' => 'Este paso aún no ha sido habilitado por tu asesor.',
                'icon' => 'info'
            ]);
            return;
        }

        if ($step) {
            $this->currentStepId = $step->id;
            // Forzamos a que si es null en DB, aquí sea string vacío
            $this->content = $step->content ?? ''; 
            $this->lastSaved = $step->updated_at ? $step->updated_at->format('h:i A') : null;

            $this->loadComments();
        }
    }

    public function loadComments()
    {
        if (!$this->currentStepId) return;

        $this->comments = ProjectComment::where('project_step_id', $this->currentStepId)
            ->with('author')
            ->latest()
            ->get();
    }

    public function addComment()
    {
        if (!$this->isReadOnly || empty(trim($this->newComment))) return;

        ProjectComment::create([
            'project_step_id' => $this->currentStepId,
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
            'type' => 'observation',
        ]);

        $this->newComment = '';
        $this->loadComments();

        $this->dispatch('swal', [
            'title' => 'Nota registrada',
            'icon' => 'success',
            'type' => 'toast',
            'position' => 'top-end'
        ]);
    }

    public function deleteComment($commentId)
    {
        if ($this->isReadOnly) {
            ProjectComment::where('id', $commentId)->delete();
            $this->loadComments();
        }
    }

    public function saveProgress()
    {
        // BLOQUEO CRÍTICO: No guardar si es asesor o si el contenido es null por error de sincronización
        if (!$this->currentStepId || $this->isReadOnly || is_null($this->content)) return;

        $step = ProjectStep::find($this->currentStepId);
        
        if ($step) {
            $step->update([
                'content' => $this->content,
                // Status dinámico basado en el contenido
                'status' => (trim($this->content) === '') ? 'pending' : 'in_progress'
            ]);
            $this->lastSaved = now()->format('h:i A');
        }
    }

    public function updatedContent()
    {
        if (!$this->isReadOnly) {
            $this->saveProgress();
        }
    }

    public function highlightSelection($selectedText)
    {
        if (!$this->isReadOnly || empty(trim($selectedText))) return;

        $cleanText = str_replace(['[[', ']]'], '', $selectedText);
        $quotedSearch = preg_quote($selectedText, '/');
        
        // Aplicamos el resaltado sobre la propiedad content
        $this->content = preg_replace("/$quotedSearch/", "[[$cleanText]]", $this->content, 1);

        $this->saveHighlightedContent();
    }

    public function removeHighlight($selectedText)
    {
        if (!$this->isReadOnly || empty(trim($selectedText))) return;
    
        // 1. Limpiamos cualquier corchete que se haya colado en la selección
        $cleanText = str_replace(['[[', ']]'], '', $selectedText);
    
        // 2. Definimos el patrón exacto con corchetes que queremos eliminar
        $target = "[[" . $cleanText . "]]";
    
        // 3. Verificamos si ese patrón existe en el contenido actual
        if (str_contains($this->content, $target)) {
            // Reemplazamos la frase marcada por la frase limpia
            $this->content = str_replace($target, $cleanText, $this->content);
            
            $this->saveHighlightedContent();
            
            $this->dispatch('swal', [
                'title' => 'Marca eliminada',
                'icon' => 'info',
                'type' => 'toast'
            ]);
        } else {
            // Si no lo encuentra con corchetes, intentamos un reemplazo literal 
            // por si el usuario seleccionó los corchetes manualmente
            $quotedSearch = preg_quote($selectedText, '/');
            $this->content = preg_replace("/$quotedSearch/", $cleanText, $this->content, 1);
            
            $this->saveHighlightedContent();
        }
    }

    private function saveHighlightedContent()
    {
        if (!$this->currentStepId) return;

        ProjectStep::where('id', $this->currentStepId)->update([
            'content' => $this->content
        ]);

        $this->dispatch('swal', ['title' => 'Sincronizado', 'icon' => 'success', 'type' => 'toast']);
    }

    public function render()
    {
        return view('livewire.project.editor-project-classroom', [
            'steps' => $this->project->steps()
                ->with('classroomStep') 
                ->orderBy('order')
                ->get(),
            'currentStep' => ProjectStep::find($this->currentStepId)
        ])->layout('layouts.app'); 
    }
}