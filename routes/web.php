<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WhatsappController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Ruta que mostrara la vista de inicio de sesión
Route::get('/', [AuthController::class, 'loginView'])->name('home');
//Ruta que mostrara la vista de registro de usuario
Route::get('/register', [AuthController::class, 'registerView'])->name('register_view');

//Grupo de rutas protegidas por el middleware 'jwt'
Route::group(['middleware' => 'jwt'], function () {
    //Ruta que hara el cerrar sesión
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    //Ruta que mostrara los datos del usuario
    Route::get('/dashboard', [AuthController::class, 'dashboardView'])->name('dashboard');
});
//Ruta que verificara el correo del usuario
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email')
    ->where('token', '^[0-9a-zA-Z_]{25,}$');
//Ruta que mostrara la vista de verificación de código
Route::get('/code', [AuthController::class, 'codeView'])->name('code.view');
//Ruta que reenviara el correo de verificación
Route::get('/resend/verification/{token}', [AuthController::class, 'resendVerification'])->name('resend.verification')
    ->where('token', '^[0-9a-zA-Z_]{25,}$');

//Ruta que hara el inicio de sesión
Route::post('/login', [AuthController::class, 'login'])->name('login');
//Ruta que hara el registro de usuario
Route::post('/register', [AuthController::class, 'register'])->name('register');
//Ruta que enviara el código de verificación
Route::post('/code/verify/{token}', [AuthController::class, 'verifyCode'])->name('verify.code')
->where('token', '^[0-9a-zA-Z_]{25,}$');

