@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Código de inicio de sesión
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Si no has sido tu, ignora este mensaje.
                </p>
            </div>
            <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                <div>
                    <p style="font-size: 120%; color: black;">{{$code}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
