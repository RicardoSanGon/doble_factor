<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Modelo para la tabla 'users'.
 * Este modelo representa la tabla 'users' en la base de datos, y contiene los campos 'id', 'email', 'password', 'phone',
 * 'token_to_verify', 'verified', 'code_to_verify' y 'timestamps'.
 *
 * @package App\Models
 * @see HasFactory Para la creación de un factory.
 * @see Authenticatable Para la autenticación de usuarios.
 * @see JWTSubject Para la implementación de JWT.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
