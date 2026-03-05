<?php

namespace App\Livewire\Admin\University;

use App\Models\University;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class ManageUniversity extends Component
{
    use WithPagination, WithFileUploads;

    public $user_id, $university_id, $nombre, $siglas, $logo, $logo_path;
    public $search = '';

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'nombre' => 'required|string|min:3',
            'siglas' => 'required|string|max:10',
            'logo' => $this->university_id ? 'nullable|image|max:1024' : 'required|image|max:1024',
        ];
    }

    public function render()
    {
        return view('livewire.admin.university.manage-university', [
            'universities' => University::where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('siglas', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(10)
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->dispatch('loading-finished');
    }

    public function edit(University $university)
    {
        $this->university_id = $university->id;
        $this->nombre = $university->nombre;
        $this->siglas = $university->siglas;
        $this->logo_path = $university->logo_path;
        $this->logo = null;
        $this->dispatch('loading-finished');
    }

    public function store()
    {
        $this->validate();

        $data = [
            'nombre' => $this->nombre,
            'siglas' => $this->siglas,
        ];

        if ($this->logo) {
            // Eliminar logo viejo si existe
            if ($this->university_id && $this->logo_path) {
                Storage::disk('public')->delete($this->logo_path);
            }
            $data['logo_path'] = $this->logo->store('universities', 'public');
        }

        University::updateOrCreate(['id' => $this->university_id], $data);

        $this->dispatch('close-modal');
        $this->dispatch('swal', [
            'title' => $this->university_id ? 'Actualizado' : 'Registrado',
            'icon' => 'success',
            'type' => 'toast'
        ]);
        $this->resetFields();
    }


    /**
     * Muestra la confirmación de eliminación (Se dispara desde el botón de la tabla)
     */
    public function confirmDelete($id)
    {
        $this->dispatch('swal', [
            'title'              => '¿Eliminar la universidad?',
            'text'               => 'Esta acción borrará todos los datos asociados.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Sí, borrar',
            'confirmButtonColor' => '#dc2626',
            'onConfirm'          => 'deleteUniversity', // JS disparará este evento si se confirma
            'id'                 => $id
        ]);
    }

    /**
     * Realiza la eliminación física tras la confirmación del usuario
     * #[On('deleteUser')] captura el evento emitido por el script de SweetAlert2
     */
    #[On('deleteUniversity')]
    public function delete($id)
    {
        $uni = University::find($id);
        if ($uni->logo_path) Storage::disk('public')->delete($uni->logo_path);
        $uni->delete();
        $this->dispatch('swal', ['title' => 'Eliminado', 'icon' => 'success', 'type' => 'toast']);
    }

    private function resetFields() {
        $this->reset(['university_id', 'nombre', 'siglas', 'logo', 'logo_path']);
    }
}
