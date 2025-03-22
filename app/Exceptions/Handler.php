<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\ViewException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Renderizado de excepciones
     *
     * Este metodo es util para manejar excepciones dentro de la aplicación, proporcionando un mensaje de error
     * y redirigiendo al usuario a una página en específico.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        /**
         * Categoría: Errores de base de datos
         * ID: DBE01
         * Descripción: Error que devuelve la base de datos al intentar realizar una operación.
         */
        if ($e instanceof QueryException) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'En este momento no podemos procesar tu solicitud, por favor intenta más tarde.');
        }
        /**
         * Categoría: Errores de validación
         * ID: VE01
         * Descripción: Devuelve los errores de validación en los formularios.
         */
        if ($e instanceof ValidationException) {
            Log::error('URL anterior detectada por Laravel: ' . url()->previous());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        /**
         * Categoría: Errores de rutas
         * ID: RE01
         * Descripción: Redirige al usuario a la página de inicio si la ruta no existe.
         */
        if ($e instanceof NotFoundHttpException) {
            Log::error($e->getMessage());
            return redirect()->route('dashboard');
        }
        /**
         * Categoría: Errores de vista
         * ID: VWE01
         * Descripción: Redirige al usuario a la página de inicio si hay un error al cargar la vista.
         */
        if ($e instanceof ViewException) {
            Log::error($e->getMessage());
            return redirect()->route('home')->with('error', 'Error al cargar la vista.');
        }
        /**
         * Categoría: Errores de autenticación
         * ID: AE01
         * Descripción: Redirige al usuario a la página de inicio si hay un error de autenticación.
         */
        if ($e instanceof JWTException) {
            Log::error($e->getMessage());
            return redirect()->route('home')->with('error', 'Acceso no autorizado.');
        }
        /**
         * Categoría: Errores de metodo no permitido
         * ID: MNE01
         * Descripción: Redirige al usuario a la página de inicio si el metodo no está permitido.
         */
        if ($e instanceof MethodNotAllowedHttpException) {
            Log::error($e->getMessage());
            return redirect()->route('home')->with('error', 'Acceso no permitido.');
        }

        return redirect()->route('home')->with('error', 'Disculpe las molestias, en este momento el sistema no esta disponible.');
    }
}
