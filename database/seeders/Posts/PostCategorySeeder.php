<?php

namespace Database\Seeders\Posts;

use Illuminate\Database\Seeder;
use App\Modules\Posts\Models\PostCategory;

class PostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['name' => 'Ansiedade',                   'slug' => 'ansiedade'],
            ['name' => 'Depressão',                   'slug' => 'depressao'],
            ['name' => 'Terapia de Casal',            'slug' => 'terapia-de-casal'],
            ['name' => 'Terapia Infantil',            'slug' => 'terapia-infantil'],
            ['name' => 'Adolescência',                'slug' => 'adolescencia'],
            ['name' => 'TDAH',                        'slug' => 'tdah'],
            ['name' => 'Autismo (TEA)',               'slug' => 'autismo-tea'],
            ['name' => 'Transtorno Bipolar',          'slug' => 'transtorno-bipolar'],
            ['name' => 'Transtornos de Personalidade', 'slug' => 'transtornos-de-personalidade'],
            ['name' => 'TEPT / Trauma',               'slug' => 'tept-trauma'],
            ['name' => 'Fobias',                      'slug' => 'fobias'],
            ['name' => 'Estresse',                    'slug' => 'estresse'],
            ['name' => 'Burnout',                     'slug' => 'burnout'],
            ['name' => 'Autoestima',                  'slug' => 'autoestima'],
            ['name' => 'Luto',                        'slug' => 'luto'],
            ['name' => 'Transtornos Alimentares',     'slug' => 'transtornos-alimentares'],
            ['name' => 'Sexualidade',                 'slug' => 'sexualidade'],
            ['name' => 'Dependência Química',         'slug' => 'dependencia-quimica'],
            ['name' => 'Psicologia Organizacional',   'slug' => 'psicologia-organizacional'],
            ['name' => 'Neuropsicologia',             'slug' => 'neuropsicologia'],
            ['name' => 'Avaliação Psicológica',       'slug' => 'avaliacao-psicologica'],
            ['name' => 'Psicoterapia Online',         'slug' => 'psicoterapia-online'],
            ['name' => 'Mindfulness',                 'slug' => 'mindfulness'],
            ['name' => 'Psicoeducação',               'slug' => 'psicoeducacao'],
            ['name' => 'Prevenção do Suicídio',       'slug' => 'prevencao-do-suicidio'],
        ];

        PostCategory::query()->upsert($rows, ['slug'], ['name']);
    }
}
