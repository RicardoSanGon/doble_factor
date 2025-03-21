@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verificación de Correo Electrónico
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Por favor, verifica tu correo electrónico para continuar
                </p>
            </div>
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                    <div >
                        <a href="{{$url}}"
                                style="background-color: #2b66bf; color: white;">
                            Enviar Verificación
                        </a>

                    </div>
                    <div>
                        <a href="{{$url_resend}}">
                            Reenviar correo
                        </a>
                    </div>
            </div>
        </div>
    </div>
@endsection

