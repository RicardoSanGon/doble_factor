@extends('layouts.app')

@section('title', 'Código de verificación')

@section('content')
    @include('loader')
    <img src="{{ asset('img/universe.webp') }}" alt="" class="fixed object-cover w-screen h-screen -z-10">
    <header class="w-full justify-center flex items-center h-20 z-10 text-white relative">
        <h1 class="text-5xl text-center">Código de inicio de sesión</h1>
    </header>
    <div class=" h-screen w-screen flex justify-center -z-20">
        <div class="rounded-md h-max w-80 my-28 backdrop-blur-md shadow-2xl shadow-black">
            <form id="loginForm" onsubmit="showLoader()" method="POST" class="py-10"
                  action="{{ route('verify.code') }}">
                @csrf
                <div class="flex justify-center">
                    <img src="{{ asset('img/mail-icon.svg') }}" alt="" class="w-20 h-20">
                </div>
                <div class="flex justify-center p-10">
                    <div>
                        <p class="text-black text-lg font-bold">Se ha enviado un código a tu correo/whatsapp, por favor
                            ingrésalo.</p>
                        <input type="text"
                               name="code"
                               id="code"
                               placeholder="Código"
                               pattern="\d*"
                               inputmode="numeric"
                               maxlength="6"
                               minlength="6"
                               required
                               class="w-60 h-10 mt-5 rounded-md p-3">
                    </div>
                </div>
                <div class="w-max ml-auto mr-auto mt-5">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button type="submit" class="w-60 h-10 mt-5
                        rounded-md
                        bg-green-500
                        text-white
                        cursor-pointer
                        hover: transition duration-500 ease-in-out hover:bg-green-900">Aceptar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection
@section('scripts')
    <script>
        //Mostrara los errores de validación
        @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
            <li class="text-center text-red-600">{{ $error }}</li>
                    @endforeach
            </ul>`,
        });
        @endif
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{session('error')}}",
        });
        @@endif

        const loader = document.getElementById('loader_view');



        //Mostrara una pantalla de carga
        function showLoader() {
            loader.style.display = 'flex';
        }

        //Ocultará la pantalla de carga cierto tiempo después de que la página se haya cargado
        window.addEventListener('beforeunload', function () {
            setTimeout(function () {
                loader.style.display = 'none';
            }, 2000);
        });
    </script>
@endsection
