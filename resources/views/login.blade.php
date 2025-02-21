@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    @include('loader')
    <img src="{{ asset('img/universe.webp') }}" alt="" class="fixed object-cover w-screen h-screen -z-10">
    <header class="w-full justify-center flex items-center h-20 z-10 text-white relative">
        <h1 class="text-5xl text-center">Inicio de sesión</h1>
    </header>
    <div class=" h-screen w-screen flex justify-center -z-20">
        <div class="rounded-md h-max w-80 my-28 backdrop-blur-md shadow-2xl shadow-black">
            <form id="loginForm" onsubmit="showLoader()" method="POST" class="py-10" action="{{ route('login') }}">
                @csrf
                <div class="flex justify-center">
                    <img src="{{ asset('img/lock.png') }}" alt="" class="w-20 h-20">
                </div>
                <div class="flex justify-center">
                    <div>
                        <input type="email"
                               name="email"
                               id="email"
                               placeholder="Correo"
                               class="w-60 h-10 mt-5 rounded-md p-3"
                               required
                               value="{{ old('email') }}">
                    </div>
                </div>
                <div class="flex justify-center">
                    <div>
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Contraseña"
                               class="w-60 h-10 mt-5 rounded-md p-3"
                               minlength="8"
                               required>
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
                <div class="flex justify-center">
                    <button type="submit" class="w-60 h-10 mt-5
                    rounded-md
                    bg-green-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-green-900">Iniciar sesión</button>
                </div>
                <div class="flex justify-center">
                    <button type="button" onclick="window.location='{{route('register_view')}}'" class="w-60 h-10 mt-5
                    rounded-md
                    bg-blue-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-blue-900">Registrarse</button>
                </div>
            </form>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        const loader = document.getElementById('loader_view');
        //Mostrara una pantalla de carga al enviar el formulario
        function showLoader(){
            loader.style.display = 'flex';
        }
        //Ocultara la pantalla de carga cierto tiempo despues de enviar el formulario
        window.addEventListener('beforeunload', function() {
            setTimeout(function(){
                loader.style.display = 'none';
            }, 4500);
        });
        //Mostrara una alerta en caso de que se cumpla alguna de las siguientes condiciones
        @if(session('verified'))
        Swal.fire({
            icon: 'success',
            title: 'Correo Verificado',
            text: "{{ session('verified') }}",
        });
        @endif
        @if(session('logout'))
        Swal.fire({
            icon: 'success',
            title: 'Sesión Cerrada',
            text: "{{ session('logout') }}",
        });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{session('error')}}",
            });
        @endif
        @if(session('registered'))
            Swal.fire({
                icon: 'success',
                title: 'Registrado',
                text: "{{session('registered')}}",
            });
        @endif
        //Mostrara una alerta de los errores de validación
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

        function reloadCaptcha(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            const captchaImage = document.getElementById('captcha-image');

            // Recarga la imagen agregando un parámetro único
            captchaImage.src = '{{ captcha_src() }}' + '?' + Math.random();
        }
    </script>
@endsection
