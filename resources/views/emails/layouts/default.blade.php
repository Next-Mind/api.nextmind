{{-- resources/views/emails/layouts/default.blade.php --}}
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <style>
        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; border-collapse:collapse !important; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:0; text-decoration:none; display:block; }
        a { text-decoration:none; }

        .bg { background:#f4f5f7; }
        .container { width:100%; max-width:600px; }
        .card { background:#ffffff; border-radius:12px; }
        .px { padding-left:24px; padding-right:24px; }
        .py { padding-top:24px; padding-bottom:24px; }
        .p-lg { padding:32px; }
        .center { text-align:center; }
        .muted { color:#6b7280; font-size:12px; line-height:1.5; }
        .brand { color:#0f172a; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji"; }
        .divider { height:1px; background:#e5e7eb; line-height:1px; }

        @media (prefers-color-scheme: dark) {
            .bg { background:#0b0c10 !important; }
            .card { background:#1b1f24 !important; }
            .brand { color:#e6e6e6 !important; }
            .muted { color:#9aa0a6 !important; }
            .divider { background:#2a2f36 !important; }
        }

        @media (max-width: 640px) {
            .p-lg { padding:24px !important; }
            .px { padding-left:16px !important; padding-right:16px !important; }
            .py { padding-top:16px !important; padding-bottom:16px !important; }
        }
    </style>
</head>
<body class="bg" style="margin:0;">
    {{-- Preheader (texto de pré-visualização oculto) - manter no body --}}
    <div style="display:none!important;visibility:hidden;opacity:0;color:transparent;height:0;width:0;overflow:hidden;">
        @yield('preheader', '')
    </div>

    <table role="presentation" width="100%" class="bg" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" class="py">
                <table role="presentation" width="100%" class="container" cellpadding="0" cellspacing="0">
                    {{-- HEADER / LOGOTIPO --}}
                    <tr>
                        <td class="px py center">
                            @hasSection('logo')
                                @yield('logo')
                            @else
                                {{-- LOGOTIPO --}}
                                @if(!empty($logoUrl ?? ''))
                                    <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} Logo" width="160" style="height:auto;">
                                @else
                                    <div class="brand" style="font-weight:700; font-size:20px;">
                                        {{ config('app.name') }}
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>

                    {{-- CARTÃO / CONTEÚDO --}}
                    <tr>
                        <td class="px">
                            <table role="presentation" width="100%" class="card" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="p-lg brand" style="font-size:16px; line-height:1.6;">
                                        @yield('content')
                                        @isset($slot)
                                            {{ $slot }}
                                        @endisset
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- RODAPÉ --}}
                    <tr><td class="px py"><div class="divider"></div></td></tr>
                    <tr>
                        <td class="px py">
                            @hasSection('footer')
                                @yield('footer')
                            @else
                                <div class="muted center">
                                    © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                                    <br>
                                    <a href="{{ $unsubscribe_url ?? '#' }}" style="color:inherit; text-decoration:underline;">Cancelar inscrição</a>
                                    ·
                                    <a href="{{ $privacy_url ?? '#' }}" style="color:inherit; text-decoration:underline;">Privacidade</a>
                                </div>
                            @endif
                        </td>
                    </tr>

                    {{-- Espaço extra p/ evitar corte em alguns clientes --}}
                    <tr><td class="py"></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!--[if mso]>
    <style type="text/css">
        .brand, .muted { font-family: Arial, sans-serif !important; }
    </style>
    <![endif]-->
</body>
</html>
