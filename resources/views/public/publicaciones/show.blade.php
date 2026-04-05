@extends('layouts.public')

@section('title', $publicacion->PUBLICACION_TITULO)

@section('content')
    @php
        $tipo = $publicacion->TIPO;
    @endphp

    @if(in_array($tipo, ['curso_reiki', 'cursos', 'terapia']))
        @include('public.publicaciones._tipos.curso')
    @elseif($tipo === 'pagina')
        @include('public.publicaciones._tipos.pagina')
    @elseif($tipo === 'galeria')
        @include('public.publicaciones._tipos.galeria')
    @elseif($tipo === 'egresado')
        @include('public.publicaciones._tipos.egresado')
    @else
        @include('public.publicaciones._tipos.pagina')
    @endif
@endsection