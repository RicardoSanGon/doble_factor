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
            <form id="loginForm" onsubmit="showLoader()" method="POST" class="py-10" action="{{ route('verify.code',['token'=>$token]) }}">
                @csrf
                <div class="flex justify-center">
                    <img src="{{ asset('img/mail-icon.svg') }}" alt="" class="w-20 h-20">
                </div>
                <div class="flex justify-center p-10">
                    <div>
                        <p class="text-black text-lg font-bold">Se ha enviado un código a tu correo/whatsapp, por favor ingrésalo.</p>
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
                    <div class="flex">
                        <img src="{!! captcha_src() !!}"
                             alt="Captcha Image"
                             class="w-max h-14 mr-5 rounded-md"
                             id="captcha-image">
                        <a class="w-1/2 h-1/2 mt-auto mb-auto flex justify-center"
                           onclick="reloadCaptcha(event)">
                            <img src="{{asset('img/reload-icon.svg')}}"
                                 alt="Reload Captcha"
                                 class="w-max h-max">
                        </a>
                    </div>
                    <div>
                        <input type="text"
                               name="captcha"
                               placeholder="Enter Captcha"
                               class="w-60 h-10 mt-5 rounded-md p-3"
                               required>
                    </div>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button type="submit" class="w-60 h-10 mt-5
                        rounded-md
                        bg-green-500
                        text-white
                        cursor-pointer
                        hover: transition duration-500 ease-in-out hover:bg-green-900">Aceptar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


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
        const loader = document.getElementById('loader_view');
        //Mostrara una pantalla de carga
        function showLoader(){
            loader.style.display = 'flex';
        }
        //Ocultará la pantalla de carga cierto tiempo después de que la página se haya cargado
        window.addEventListener('beforeunload', function() {
            setTimeout(function(){
                loader.style.display = 'none';
            }, 2000);
        });
        function reloadCaptcha(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            const captchaImage = document.getElementById('captcha-image');

            // Recarga la imagen agregando un parámetro único
            captchaImage.src = '{{ captcha_src() }}' + '?' + Math.random();
        }
    </script>
@endsection
