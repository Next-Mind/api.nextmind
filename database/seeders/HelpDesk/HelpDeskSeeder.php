<?php

namespace Database\Seeders\HelpDesk;

use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Models\TicketStatus;
use App\Modules\HelpDesk\Models\TicketSubcategory;
use Illuminate\Database\Seeder;

class HelpDeskSeeder extends Seeder
{
    public function run(): void
    {
        // -------- STATUSES (curto e prático) --------
        $statuses = [
            ['name' => 'Aberto',               'slug' => 'aberto',             'is_final' => false, 'position' => 1],
            ['name' => 'Em análise',           'slug' => 'em-analise',         'is_final' => false, 'position' => 2],
            ['name' => 'Aguardando usuário',   'slug' => 'aguardando-usuario', 'is_final' => false, 'position' => 3],
            ['name' => 'Em desenvolvimento',   'slug' => 'em-desenvolvimento', 'is_final' => false, 'position' => 4],
            ['name' => 'Resolvido',            'slug' => 'resolvido',          'is_final' => true,  'position' => 5],
            ['name' => 'Cancelado',            'slug' => 'cancelado',          'is_final' => true,  'position' => 6],
        ];

        foreach ($statuses as $s) {
            TicketStatus::firstOrCreate(
                ['slug' => $s['slug']],
                [
                    'name'      => $s['name'],
                    'is_final'  => $s['is_final'],
                    'position'  => $s['position'] ?? null,
                ]
            );
        }

        // -------- CATEGORIES ENXUTAS --------
        $categories = [
            [
                'name' => 'Bugs',
                'slug' => 'bugs',
                'position' => 1,
                'subs' => [
                    ['name' => 'App travando/fechando', 'slug' => 'crash', 'position' => 1],
                    ['name' => 'Erro de tela/fluxo',     'slug' => 'erro-tela', 'position' => 2],
                    ['name' => 'Dados incorretos',       'slug' => 'dados-incorretos', 'position' => 3],
                ],
            ],
            [
                'name' => 'Sugestão de melhoria',
                'slug' => 'sugestao-melhoria',
                'position' => 2,
                'subs' => [
                    ['name' => 'UX/Interface',           'slug' => 'ux-interface', 'position' => 1],
                    ['name' => 'Novos recursos',         'slug' => 'novos-recursos', 'position' => 2],
                    ['name' => 'Acessibilidade',         'slug' => 'acessibilidade', 'position' => 3],
                ],
            ],
            [
                'name' => 'Acesso/Conta',
                'slug' => 'acesso-conta',
                'position' => 3,
                'subs' => [
                    ['name' => 'Login/SSO',              'slug' => 'login-sso', 'position' => 1],
                    ['name' => 'Permissões/Perfil',      'slug' => 'permissoes-perfil', 'position' => 2],
                    ['name' => 'Recuperar senha',        'slug' => 'recuperar-senha', 'position' => 3],
                ],
            ],
            [
                'name' => 'Atendimento & Agenda',
                'slug' => 'atendimento-agenda',
                'position' => 4,
                'subs' => [
                    ['name' => 'Agendamento',            'slug' => 'agendamento', 'position' => 1],
                    ['name' => 'Chat/Contatos',          'slug' => 'chat-contatos', 'position' => 2],
                    ['name' => 'Prontuário/Notas',       'slug' => 'prontuario-notas', 'position' => 3],
                ],
            ],
        ];

        foreach ($categories as $c) {
            $category = TicketCategory::firstOrCreate(
                ['slug' => $c['slug']],
                [
                    'name'      => $c['name'],
                    'position'  => $c['position'] ?? null,
                ]
            );

            foreach ($c['subs'] as $sub) {
                TicketSubcategory::firstOrCreate(
                    ['slug' => $sub['slug']],
                    [
                        'name'               => $sub['name'],
                        'position'           => $sub['position'],
                        'ticket_category_id' => $category->getKey(),
                    ]
                );
            }
        }
    }
}
