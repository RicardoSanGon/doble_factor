<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     * @throws JWTException
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        /**
         * Dentro de este metodo del middleware, se verifica si el token (JWT) es válido,
         * obteniendo el token (JWT) de la cookie 'jwt' y autenticando el usuario con 'JWTAuth',
         * en caso de que falle la autenticación, se lanza una excepción 'JWTException'.
         */
        try {
            $token = $request->cookie('jwt');
            if (!$token) {
                throw new JWTException();
            }
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate($token);
        } catch (JWTException $e) {
            throw new JWTException();
        }
        return $next($request);
    }
}
