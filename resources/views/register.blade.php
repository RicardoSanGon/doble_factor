@extends('layouts.app')

@section('title', 'Registro')

@section('content')
    @include('loader')
    <img src="{{ asset('img/universe.webp') }}" alt="" class="fixed object-cover w-screen h-screen -z-10">
    <header class="w-full justify-center flex items-center h-20 z-10 text-white relative">
        <h1 class="text-5xl">Registro</h1>
    </header>
    <div class=" h-screen w-screen flex justify-center -z-20">
        <div class="h-fit rounded-md w-80 my-14 backdrop-blur-md shadow-2xl shadow-black">
            <form id="register_form"
                  method="POST"
                  class="py-10"
                  action="{{ route('register') }}">
                @method('POST')
                @csrf
                <div class="flex justify-center">
                    <img src="{{ asset('img/user-icon.svg') }}" alt="" class="w-20 h-20">
                </div>
                <div class="flex justify-center">
                    <input type="email"
                           name="email"
                           id="email"
                           placeholder="Correo"
                           required
                           class="w-60 h-10 mt-5 rounded-md p-3" value="{{ old('email') }}">
                </div>
                <div class="flex justify-center">
                    <input type="text"
                           name="phone"
                           id="phone"
                           placeholder="Numero de telefono"
                           class="w-60 h-10 mt-5 rounded-md p-3"
                           pattern="\d*"
                           inputmode="numeric"
                           required
                           maxlength="10"
                           minlength="10" value="{{ old('phone') }}">
                </div>
                <div class="flex justify-center">
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Contraseña"
                           required
                           class="w-60 h-10 mt-5 rounded-md p-3">
                </div>
                <div class="flex justify-center">
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           placeholder="Confirmar contraseña"
                           required
                           class="w-60 h-10 mt-5 rounded-md p-3">
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
                <div class="flex justify-center">
                    <button type="submit" class="w-60 h-10 mt-5
                    rounded-md
                    bg-green-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-green-900">Registrarse</button>
                </div>
                <div class="flex justify-center">
                    <button type="button" onclick="window.location='{{route('home')}}'" class="w-60 h-10 mt-5
                    rounded-md
                    bg-blue-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-blue-900">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        //Mostrar mensajes de error.
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{session('error')}}",
        });
        @endif
        //Mostar mensajes de error de validación.
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
        //Validar que las contraseñas coincidan antes de enviar el formulario.
        document.getElementById('register_form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            if (password !== passwordConfirmation) {
                event.preventDefault();
                alert('Las contraseñas no coinciden');
            }
            else {
                loader.style.display = 'flex';
            }
        });
        //Ocultar la pantalla de carga cierto tiempo despues.
        window.addEventListener('beforeunload', function() {
            setTimeout(function(){
                loader.style.display = 'none';
            }, 4500);
        });
        function reloadCaptcha(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            const captchaImage = document.getElementById('captcha-image');

            // Recarga la imagen agregando un parámetro único
            captchaImage.src = '{{ captcha_src() }}' + '?' + Math.random();
        }
    </script>
@endsection
