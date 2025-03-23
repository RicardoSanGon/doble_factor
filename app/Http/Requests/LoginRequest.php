<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected $redirect = '/login';
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
         * En este metodo se definen las reglas de validación para los campos 'email' y 'password' del formulario de inicio de sesión.
         * Válida que el campo 'email' sea requerido, tenga un formato de correo electrónico, una longitud máxima de 30 caracteres y cumpla con la expresión regular,
         * y que el campo 'password' sea requerido y tenga una longitud mínima de 8 caracteres.
         */
        return [
            'email' => 'required|email|max:30|regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:8',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
