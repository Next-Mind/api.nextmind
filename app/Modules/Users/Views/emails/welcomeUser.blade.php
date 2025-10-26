@extends('emails.layouts.default')

@section('title', 'Bem-vindo!')

@section('preheader', 'Sua conta foi criada com sucesso.')

@section('logo')
    <a href="{{ config('app.url') }}">
        <img src="https://nextmind.tech/images/nextmind.png" alt="{{ config('app.name') }} Logo" width="160">
    </a>
@endsection

@section('content')
    <h1 style="margin:0 0 12px; font-size:22px;">Olá, {{ $user->name }}!</h1>
    <p style="margin:0 0 16px;">
        Seja bem-vindo ao <strong>{{ config('app.name') }}</strong>. Seu cadastro foi concluído.
    </p>
    <p style="margin:0;">
        Por gentileza, não responda este e-mail.
    </p>
@endsection

@section('footer')
    <div class="muted center">
        {{ config('app.name') }} · Rua Paschoal Marmo, 1888 · Limeira/SP<br>
        Suporte: <a href="mailto:suporte@nextmind.tech" style="color:inherit; text-decoration:underline;">suporte@nextmind.tech</a>
    </div>
@endsection
