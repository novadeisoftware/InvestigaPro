<?php

namespace App\Livewire\Admin\User;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class ManageUser extends Component
{
    /* |--------------------------------------------------------------------------
    | Traits y Propiedades
    |--------------------------------------------------------------------------
    */
    use WithPagination; // Habilita la paginación dinámica sin recargar la página

    // Propiedades vinculadas al formulario (wire:model)
    public $user_id, $name, $email, $password;
    
    // Filtro de búsqueda
    public $search = '';

    // Sincroniza la propiedad $search con la URL del navegador (?search=...)
    protected $queryString = ['search'];

    /* |--------------------------------------------------------------------------
    | Reglas de Validación
    |--------------------------------------------------------------------------
    */
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => [
                'required', 
                'email', 
                // Valida unicidad pero ignora al usuario actual si estamos editando
                Rule::unique('users')->ignore($this->user_id)
            ],
            // Contraseña obligatoria solo en registros nuevos; opcional en ediciones
            'password' => $this->user_id ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    /* |--------------------------------------------------------------------------
    | Renderizado de Vista
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        // Si el usuario autenticado NO es superadmin, lo redirigimos al dashboard
        if (!auth()->user()->is_superadmin) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function render()
    {
        // Consulta filtrada: Busca por nombre o email según el valor de $search
        return view('livewire.admin.user.manage-user', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%')
                            ->orderBy('id', 'desc')
                            ->paginate(10)
        ]);
    }

    /* |--------------------------------------------------------------------------
    | Lógica de Búsqueda y Limpieza
    |--------------------------------------------------------------------------
    */

    // Hook de Livewire: Si el usuario escribe en el buscador, resetea a la página 1
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Limpia las variables del formulario para evitar residuos de datos anteriores
    private function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->user_id = null;
    }

    /* |--------------------------------------------------------------------------
    | Gestión de Modal (Crear / Editar)
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->resetFields(); // Asegura campos vacíos para nuevo registro
        $this->dispatch('loading-finished'); // Notifica al modal que deje de mostrar el preloader
    }

    public function edit(User $user)
    {
        // Carga los datos del modelo en las propiedades del componente
        $this->user_id = $user->id;
        $this->name    = $user->name;
        $this->email   = $user->email;
        $this->password = ''; // Nunca cargamos el hash de la contraseña por seguridad

        $this->dispatch('loading-finished'); // Notifica al modal para mostrar el formulario
    }

    /* |--------------------------------------------------------------------------
    | Operaciones CRUD (Store y Delete)
    |--------------------------------------------------------------------------
    */
    
    /**
     * Procesa tanto la creación como la actualización de usuarios
     */
    public function store()
    {
        $this->validate(); // Dispara el método rules() definido arriba

        // Detecta si es actualización antes de limpiar el user_id para el mensaje
        $isUpdate = !empty($this->user_id);
        $message  = $isUpdate ? 'Usuario actualizado con éxito' : 'Usuario registrado con éxito';

        $userData = [
            'name'  => $this->name,
            'email' => $this->email,
        ];

        // Encripta la contraseña solo si el campo no está vacío (permite omitir cambio en edición)
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        // Ejecuta la operación en BD (si id existe, actualiza; si no, crea)
        User::updateOrCreate(['id' => $this->user_id], $userData);

        $this->dispatch('close-modal'); // Notifica al JavaScript que cierre el modal
        $this->resetFields();           // Limpia el formulario
   
        // Dispara notificación Toast a través del script personalizado de SweetAlert2
        $this->dispatch('swal', [
            'type'     => 'toast',
            'title'    => $message,
            'icon'     => 'success',
            'position' => 'bottom-end'
        ]);
    }

    /**
     * Muestra la confirmación de eliminación (Se dispara desde el botón de la tabla)
     */
    public function confirmDelete($id)
    {
        $this->dispatch('swal', [
            'title'              => '¿Eliminar el usuario?',
            'text'               => 'Esta acción borrará todos los datos asociados.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Sí, borrar',
            'confirmButtonColor' => '#dc2626',
            'onConfirm'          => 'deleteUser', // JS disparará este evento si se confirma
            'id'                 => $id
        ]);
    }

    /**
     * Realiza la eliminación física tras la confirmación del usuario
     * #[On('deleteUser')] captura el evento emitido por el script de SweetAlert2
     */
    #[On('deleteUser')] 
    public function delete($id)
    {
        if ($id === auth()->id()) {
            // Disparar error: "No puedes eliminar tu propia cuenta"
                  // Manejo de error si el usuario fue borrado por otro administrador simultáneamente
                $this->dispatch('swal', [
                    'title' => '¡Error!',
                    'text'  => 'No se puedes eliminar tu propia cuenta',
                    'icon'  => 'error'
                ]);
        }
        else{

            $user = User::find($id);

            if ($user) {
                $user->delete();

                $this->dispatch('swal', [
                    'type'     => 'toast',
                    'title'    => 'Usuario eliminado correctamente',
                    'icon'     => 'success',
                    'position' => 'bottom-end'
                ]);
            } else {
                // Manejo de error si el usuario fue borrado por otro administrador simultáneamente
                $this->dispatch('swal', [
                    'title' => '¡Error!',
                    'text'  => 'No se pudo encontrar el registro.',
                    'icon'  => 'error'
                ]);
            }

        }

    }
}