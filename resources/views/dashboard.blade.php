@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('loader')
    <img src="{{ asset('img/universe.webp') }}" alt="" class="fixed object-cover w-screen h-screen -z-10">
    <header class="w-full justify-center flex items-center h-20 z-10 text-white relative">
        <h1 class="text-5xl">Dashboard</h1>
    </header>
    <div class=" h-screen w-screen flex justify-center -z-20">
        <div class="h-fit rounded-md w-80 my-28 backdrop-blur-md shadow-2xl shadow-black py-20">
            <div class="flex justify-center">
                <img src="{{ asset('img/user-icon.svg') }}" alt="" class="w-20 h-20">
            </div>
            <div class="flex justify-center">
                <p class="text-black">
                    Bienvenido
                <p class="font-bold ml-2"> {{Auth::user()->email}}</p>
                </p>
            </div>
            <div class="flex justify-center">
                <a class="w-60 h-10 mt-5
                    rounded-md
                    bg-red-500
                    text-white
                    text-center
                    p-2
                    cursor-pointer
                    hover: transition duration-500 ease-in-out hover:bg-red-900"
                   href="{{route('logout')}}"
                   onclick="clearHistoryAndLogout(event)">Cerrar sesión</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function clearHistoryAndLogout(event) {
            event.preventDefault();
            history.pushState(null, null, location.href);
            window.addEventListener('popstate', function () {
                history.pushState(null, null, location.href);
            });
            setTimeout(function () {
                window.location.href = '{{ route('logout') }}';
            }, 1000);
        }
    </script>
@endsection
