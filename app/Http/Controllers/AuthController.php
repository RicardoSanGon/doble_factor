<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\CodeEmail;
use App\Mail\EmailVerification;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Mews\Captcha\Facades\Captcha;
use Random\RandomException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Resend\Laravel\Facades\Resend;

/**
 * Class AuthController
 *
 * Esta clase contiene los metodos para el manejo de la autenticación de usuarios,
 * registro de usuarios, verificación de email y código de verificación.
 *
 * @package App\Http\Controllers
 * @see Controller Para la creación de un controlador.
 * @see WhatsappController Para el envío de mensajes de WhatsApp.
 * @see User Para la creación de un usuario.
 * @see LoginRequest Para la validación de los datos de inicio de sesión.
 * @see RegisterRequest Para la validación de los datos de registro.
 * @see CodeRequest Para la validación del código de verificación.
 * @see CodeEmail Para el envío de correos electrónicos con el código de verificación.
 * @see EmailVerification Para el envío de correos electrónicos de verificación.
 * @see JWTAuth Para la autenticación de usuarios.
 * @see Hash Para el cifrado de contraseñas.
 * @see DB Para la transacción con la base de datos.
 * @see Mail Para el envío de correos electrónicos.
 * @see URL Para la generación de URLs.
 * @see ValidationException Para el manejo de excepciones de validación.
 * @see Log Para el registro de errores.
 */
class AuthController extends Controller
{
    /**
     * Controlador para el envío de mensajes de WhatsApp.
     *
     * @var WhatsappController
     */
    private $whatsapp_controller;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->whatsapp_controller = new WhatsappController();
    }

    /**
     * Muestra la vista de inicio de sesión.
     *
     * @return \Illuminate\View\View
     */
    public function loginView()
    {
        return view('login', ['captcha' => captcha_img()]);
    }

    /**
     * Muestra la vista de registro.
     *
     * @return \Illuminate\View\View
     */
    public function registerView()
    {
        return view('register', ['captcha' => captcha_img()]);
    }

    /**
     * Muestra la vista del dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboardView()
    {
        return view('dashboard');
    }

    /**
     * Muestra la vista del código de verificación.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function codeView($token)
    {
        return view('code', ['token' => $token, 'captcha' => captcha_img()]);
    }

    /**
     * Inicia sesión de un usuario.
     *
     * Dentro de este metodo se valida que los datos ingresados por el usuario(Email y contraseña) sean correctos,
     * además de identificar si está verificado, para garantizar que el correo que se está utilizando es válido.
     * Si los datos son correctos, se genera un código de verificación y se envía al correo del usuario.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException|\Random\RandomException
     * @see LoginRequest Para la validación de los datos de inicio de sesión.
     * @see User Para la creación de un usuario.
     * @see CodeEmail Para el envío de correos electrónicos con el código de verificación.
     * @see Hash Para el cifrado de contraseñas.
     * @see ValidationException Para el manejo de excepciones de validación.
     * @see RandomException Para el manejo de excepciones de números aleatorios.
     * @see Mail Para el envío de correos electrónicos.
     * @see URL Para la generación de URLs.
     * @see WhatsappController::sendMessage() Para el envío de mensajes de WhatsApp.
     */
    public function login(LoginRequest $request)
    {
        $request->validated();
        $this->verfyCaptcha($request->captcha);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'user' => 'Usuario no encontrado'
            ]);
        }
        if (!$user->verified) {
            throw ValidationException::withMessages([
                'user' => 'Usuario no verificado'
            ]);
        }
        if (Hash::check($request->password, $user->password)) {
            $user->token_to_verify = $this->generateRandomString();
            $code = random_int(100000, 999999);
            $user->code_to_verify = Hash::make($code);
            $mail = new CodeEmail($code);
            Resend::emails()->send(['from' => 'noreply@ricardosg.icu', 'to' => $user->email, 'html' => $mail->render(), 'subject' => 'Verificación de correo']);
            $this->whatsapp_controller->sendMessage('Se ha dectectado un inicio de sesión en tu cuenta *' . $user->email . '* con el código de verificación: *' . $code . '*. Si no fuiste tú, por favor ignora este mensaje.');
            $user->save();
            return redirect()->route('code.view', ['token' => $user->token_to_verify]);
        } else {
            throw ValidationException::withMessages([
                'credentials' => 'Credenciales incorrectas'
            ]);
        }
    }

    /**
     * Cierra la sesión de un usuario.
     *
     * En este metodo se borran las cookies que se hayan generado para la autenticación del usuario,
     * y se redirige al usuario a la página de inicio.
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        $cookie = cookie('jwt', null, -1);
        session()->flash('logout', 'Sesión cerrada correctamente');
        return redirect()->route('home')->cookie($cookie);
    }

    /**
     * Registro de usuarios.
     *
     * Mediante este metodo todos los usuarios nuevos seran capaces de crear una cuenta dentro del sistema,
     * para esto se valida que los datos ingresados sean correctos y se envía un correo de verificación al usuario,
     * esto mediante el uso de una transacción para garantizar la integridad de los datos y de la misma base de datos.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     * @throws Exception
     * @see RegisterRequest Para la validación de los datos de registro.
     * @see User Para la creación de un usuario.
     * @see EmailVerification Para el envío de correos electrónicos de verificación.
     * @see Hash Para el cifrado de contraseñas.
     * @see DB Para la transacción con la base de datos.
     * @see Mail Para el envío de correos electrónicos.
     * @see URL Para la generación de URLs.
     * @see $this::generateRandomString() Para la generación de un token aleatorio.
     */

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        $request->validated();
        $this->verfyCaptcha($request->captcha);
        $token = $this->generateRandomString();
        $signed_url = URL::temporarySignedRoute(
            'verify.email',
            now()->addMinutes(30),
            ['token' => $token]
        );
        $resend_url = URL::signedRoute(
            'resend.verification',
            ['token' => $token]
        );
        $mail = new EmailVerification($signed_url, $resend_url);
        try {
            $user = new User();
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->token_to_verify = $token;
            Resend::emails()->send(['from' => 'noreply@ricardosg.icu', 'to' => $user->email, 'html' => $mail->render(), 'subject' => 'Verificación de correo']);
            $user->save();
            DB::commit();
            session()->flash('registered', 'Usuario registrado correctamente, verifica tu email');
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            session()->flash('error', 'Error al registrar el usuario, intenta más tarde');
            return redirect()->back();
        }
    }

    /**
     * Verificación de email.
     *
     * Este metodo se encarga de verificar el email del usuario, si el token es correcto se cambia el estado de verificación
     *
     * @param $token
     * @return RedirectResponse
     * @see User Para la creación de un usuario.
     */
    function verifyEmail($token)
    {
        $user = User::where('token_to_verify', $token)->first();
        if ($user) {
            $user->verified = true;
            $user->save();
            session()->flash('verified', 'Email verificado correctamente');
            return redirect()->route('home');
        }
        return redirect()->back();
    }

    /**
     * Verificación de código.
     *
     * Este metodo se encarga de verificar el código de verificación del usuario, si el código es correcto se genera un token (JWT),
     * el cual se almacena en una cookie y se redirige al usuario al dashboard.
     *
     * @param CodeRequest $request
     * @param $token
     * @return RedirectResponse
     * @throws JWTException En caso de haber un error de generación del token (JWT).
     * @throws ValidationException En caso de que el código sea incorrecto.
     * @see CodeRequest Para la validación del código de verificación.
     * @see User::where() Para la busqueda de un usuario.
     * @see JWTAuth Para la creación del token (JWT).
     * @see Hash::check() Para compara texto encriptado con texto plano.
     */
    public function verifyCode(CodeRequest $request, $token)
    {
        $request->validated();
        $this->verfyCaptcha($request->captcha);
        $user = User::where('token_to_verify', $token)->first();
        if (!$user) {
            return redirect()->route('home')->with('error', 'Acceso no permitido.');
        }
        if (Hash::check($request->code, $user->code_to_verify)) {

            if (!$token = JWTAuth::fromUser($user)) {
                throw new JWTException('No se pudo crear el token');
            }
            $user->code_to_verify = null;
            $user->token_to_verify = null;
            $cookie = cookie('jwt', $token, 60 * 24);
            $user->save();
            return redirect()->route('dashboard')->cookie($cookie);
        } else {
            throw ValidationException::withMessages([
                'code' => 'Código incorrecto'
            ]);
        }
    }

    /**
     * Reenvío de email de verificación.
     *
     * Si el correo anterior expiro y ya no se puede verificar el email, mediante este metodo se puede reenviar el correo de verificación.
     *
     * @param string $token Para identificar el usuario
     * @return RedirectResponse
     * @see User::where() Para la busqueda de un usuario.
     * @see URL::temporarySignedRoute() Para la generación de una URL temporal firmada.
     * @see URL::signedRoute() Para la generación de una URL firmada.
     * @see EmailVerification Para el envío de correos electrónicos de verificación.
     * @see Mail Para el envío de correos electrónicos.
     */
    public function resendVerification($token)
    {
        $user = User::where('token_to_verify', $token)->first();
        if ($user) {
            $signed_url = URL::temporarySignedRoute(
                'verify.email',
                now()->addMinutes(30),
                ['token' => $token]
            );
            $resend_url = URL::signedRoute(
                'resend.verification',
                ['token' => $token]
            );
            $mail = new EmailVerification($signed_url, $resend_url);
            Resend::emails()->send(['from' => 'noreply@ricardosg.icu', 'to' => $user->email, 'html' => $mail->render(), 'subject' => 'Verificación de correo']);
            session()->flash('resent', 'Email reenviado correctamente');
            return redirect()->route('home');
        }
        return redirect()->back();
    }

    /**
     * Genera una cadena aleatoria.
     *
     * Este metodo se encarga de generar una cadena aleatoria de 25 caracteres.
     *
     * @param int $length
     * @return string
     * @throws RandomException
     */
    function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Verifica el captcha.
     *
     * En este metodo se verifica que el captcha ingresado por el usuario sea correcto,
     * en caso contrario se lanza una excepción de validación.
     *
     * @param $captcha
     * @return void
     * @throws ValidationException
     */
    function verfyCaptcha($captcha)
    {
        if (!Captcha::check($captcha)) {
            throw ValidationException::withMessages([
                'captcha' => 'Wrong captcha'
            ]);
        }
    }

}
