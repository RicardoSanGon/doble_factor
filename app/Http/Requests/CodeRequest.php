<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CodeRequest extends FormRequest
{
    protected $redirect = '/code';
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
         * En este metodo se definen las reglas de validación para el campo 'code' del formulario de verificación de código.
         * Válida que el campo 'code' sea requerido, numérico y tenga una longitud de 6 caracteres.
         */
        return [
            'code' => 'required|numeric|digits:6',
            'captcha' => 'required'
        ];
    }
}
