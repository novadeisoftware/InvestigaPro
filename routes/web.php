<?php

use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\DeleteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// 1. REDIRECCIÓN INICIAL
Route::get('/', function () {
    return view('welcome');
});

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

    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'InvestigaPro']);
    })->name('dashboard');

    // Calendario
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