<?php

use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\DeleteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Livewire\Admin\User\ManageUser;
use App\Livewire\Admin\University\ManageUniversity;
use App\Livewire\Project\ManageProject;
use App\Livewire\Project\EditorProject;
use App\Livewire\Classroom\ManageClassroom;
use App\Livewire\Classroom\ManageClassroomSteps;
use App\Livewire\Classroom\JoinClassroom;
use App\Livewire\Dashboard\ManageDashboard;
use App\Livewire\Classroom\ShowClassroom;
use App\Livewire\Project\EditorProjectClassroom;
use App\Livewire\Subscription\ManageSubscription;
use App\Http\Controllers\Payment\IzipayController;
use App\Livewire\Classroom\DashboardClassroom;
use App\Livewire\Project\SetupProject;
use App\Livewire\Payment\IzipayCheckout;
use App\Http\Controllers\Project\ExportController;


Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});


// 1. REDIRECCIÓN INICIAL
Route::get('/', function () {
    return view('welcome');
});

// MUEVE ESTA LÍNEA FUERA DEL GRUPO Route::middleware(['auth'...])
Route::get('/join/{code}', JoinClassroom::class)->name('classroom.autojoin');

// 2. RUTAS PROTEGIDAS
Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // --- ESTA ES LA RUTA DE ELIMINACIÓN (Fuera del dashboard) ---
    Route::delete('/user/delete-account-direct', function (Request $request, DeleteUser $deleter) {
        if (! Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.'], 'deleteUser');
        }

        $deleter->delete(auth()->user());
        return redirect('/login');
    })->name('user.destroy.manual');



    Route::get('/dashboard', ManageDashboard::class)->name('dashboard');

    // Ruta para la gestión de usuarios
    Route::get('/admin/users', ManageUser::class)->name('admin.users');

    // Ruta para la gestión de universidades
    Route::get('/admin/university', ManageUniversity::class)->name('admin.university');
    
    // Ruta para la gestión projectos
    Route::get('/project', ManageProject::class)->name('project');

    // EDITOR: Donde el alumno redacta los 10 pasos
    // URL: investigapro.test/investigaciones/550e8400-e29b-41d4-a716-446655440000
    // Usamos {project:uuid} para que Laravel busque automáticamente por UUID
    Route::get('/project/{project:uuid}', EditorProject::class)->name('projects.show');

    Route::get('/proyectos/{project:uuid}/configuracion', SetupProject::class)->name('projects.setup');
    
    // Ruta para la gestión classrooom
    Route::get('/classroom', ManageClassroom::class)->name('classroom');

    // Ruta para la gestión subscripciones
    Route::get('/subscription', ManageSubscription::class)->name('subscription');

    // Ruta específica para que el alumno vea su aula y sus pasos
    Route::get('/my-classroom/{classroom}', ShowClassroom::class)->name('student.classroom.show');

    Route::get('/classroom/editor/{project:uuid}/{stepId?}', EditorProjectClassroom::class)
    ->name('classroom.editor');

    Route::get('/classroom/{classroom}', DashboardClassroom::class)
    ->name('classroom.show')
    ->middleware('auth');

    Route::get('/projects/{project}/export-word', [ExportController::class, 'exportToWord'])->name('projects.export-word');

    // Ruta para iniciar el proceso de pago
    Route::get('/checkout/izipay/{payment:niubiz_order_id}', IzipayCheckout::class)
    ->name('payment.izipay.checkout')
    ->middleware('auth');

    // Izipay usa una respuesta IPN (servidor a servidor) y una de retorno (cliente)
    Route::post('/payment/izipay/result', [IzipayController::class, 'result'])
        ->name('payment.izipay.result');


    // Calendario
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');


    // Usuarios
    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    // Perfil de Usuario
    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    // Elementos de UI y otros...
    Route::get('/form-elements', function () { return view('pages.form.form-elements', ['title' => 'Form Elements']); })->name('form-elements');
    Route::get('/basic-tables', function () { return view('pages.tables.basic-tables', ['title' => 'Basic Tables']); })->name('basic-tables');
    Route::get('/line-chart', function () { return view('pages.chart.line-chart', ['title' => 'Line Chart']); })->name('line-chart');
    Route::get('/bar-chart', function () { return view('pages.chart.bar-chart', ['title' => 'Bar Chart']); })->name('bar-chart');
    Route::get('/alerts', function () { return view('pages.ui-elements.alerts', ['title' => 'Alerts']); })->name('alerts');
    Route::get('/avatars', function () { return view('pages.ui-elements.avatars', ['title' => 'Avatars']); })->name('avatars');
    Route::get('/badge', function () { return view('pages.ui-elements.badges', ['title' => 'Badges']); })->name('badges');
    Route::get('/buttons', function () { return view('pages.ui-elements.buttons', ['title' => 'Buttons']); })->name('buttons');
    Route::get('/image', function () { return view('pages.ui-elements.images', ['title' => 'Images']); })->name('images');
    Route::get('/videos', function () { return view('pages.ui-elements.videos', ['title' => 'Videos']); })->name('videos');
    Route::get('/blank', function () { return view('pages.blank', ['title' => 'Blank']); })->name('blank');
});

// 3. RUTAS PÚBLICAS
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');