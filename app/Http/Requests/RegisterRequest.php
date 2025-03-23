<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    protected $redirect = '/register';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        /**
         * En este metodo se definen las reglas de validación para los campos 'email', 'password' y 'phone' del formulario de registro.
         * Válida que el campo 'email' sea requerido, tenga un formato de correo electrónico, una longitud máxima de 30 caracteres, cumpla con la expresión regular y sea único en la tabla 'users',
         * que el campo 'password' sea requerido, tenga una longitud mínima de 8 caracteres y sea confirmado,
         * y que el campo 'phone' sea requerido, numérico, tenga una longitud de 10 caracteres y sea único en la tabla 'users'.
         */
        return [
            'email' => 'required|email|unique:users|max:30|regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$/',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'phone' => 'required|numeric|digits_between:10,10|unique:users',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
