@extends('emails.layouts.default')

@section('title', 'Parabéns! Seu cadastro foi aprovado.')

@section('preheader', 'Agora você pode usufruir de nosso sistema.')

@section('logo')
    <a href="{{ config('app.url') }}">
        <img src="https://nextmind.tech/images/nextmind.png" alt="{{ config('app.name') }} Logo" width="160">
    </a>
@endsection

@section('content')
    <h1 style="margin:0 0 12px; font-size:22px;">Olá, {{ $user->name }}!</h1>
    <p style="margin:0 0 16px;">
        Boas notícias! Seu cadastro foi revisado e aprovado pela nossa equipe de moderação. Por favor, clique no link abaixo e realize o login em nossa plataforma.
    </p>
    <a href="{{ $url }}" style="margin:0;">
        Clique aqui
    </a>
@endsection

@section('footer')
    <div class="muted center">
        {{ config('app.name') }} · Rua Paschoal Marmo, 1888 · Limeira/SP<br>
        Suporte: <a href="mailto:suporte@nextmind.tech" style="color:inherit; text-decoration:underline;">suporte@nextmind.tech</a>
    </div>
@endsection
