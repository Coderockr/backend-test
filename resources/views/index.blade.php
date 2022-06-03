@extends('app')

@section('content')

<div class="h-screen flex w-full bg-img">
    <div class="vx-col flex items-center justify-center flex-col sm:w-1/2 md:w-3/5 lg:w-3/4 xl:w-1/2 mx-auto text-center">
        <img src="{{ asset('images/pages/500.png') }}" alt="graphic-500" class="mx-auto mb-4">
        <h1 class="mb-12 text-5xl d-theme-heading-color">Servidor Interno</h1>
        <!-- <p class="mb-16 d-theme-text-inverse">Há um problema com o recurso que você está procurando e não pode ser exibido.</p> -->
        <p class="mb-16 d-theme-text-inverse">O servidor web foi instalado com êxito e está funcionando.
            Para documentação e suporte online, consulte diones.souza.calca@gmail.com.
            <br>Obrigado por usar api de investimentos.</p>
        <button type="button" name="button" class="btn vs-component vx-button vx-button-primary vx-button-filled large" onclick="window.location.href = '{{env('APP_HOST')}}'">
            <span class="vx-button-backgroundx vx-button--background" style="opacity: 1; left: 20px; top: 20px; width: 0px; height: 0px; transition: width 0.3s ease 0s, height 0.3s ease 0s, opacity 0.3s ease 0s;">
            </span>
            <span class="vx-button-text vx-button--text">Ir para página inícial</span>
            <span class="vx-button-linex" style="top: auto; bottom: -2px; left: 50%; transform: translate(-50%);"></span>
        </button>
    </div>
</div>

@endsection