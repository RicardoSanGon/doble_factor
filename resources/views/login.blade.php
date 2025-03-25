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
            <form id="loginForm" method="POST" class="py-10" action="{{ route('login') }}">
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
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="w-60 h-10 mt-5
                    rounded-md
                    bg-green-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-green-900">Iniciar sesión
                    </button>
                </div>
                <div class="flex justify-center">
                    <button type="button" onclick="window.location='{{route('register_view')}}'" class="w-60 h-10 mt-5
                    rounded-md
                    bg-blue-500
                    text-white
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-blue-900">Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection
@section('scripts')
    <script>

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const response = grecaptcha.getResponse();
            if (response.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, completa el captcha.',
                });
            }
            else {
                showLoader();
            }
        });

        const loader = document.getElementById('loader_view');

        //Mostrara una pantalla de carga al enviar el formulario
        function showLoader() {
            loader.style.display = 'flex';
        }

        //Ocultara la pantalla de carga cierto tiempo despues de enviar el formulario
        window.addEventListener('beforeunload', function () {
            setTimeout(function () {
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

    </script>
@endsection
