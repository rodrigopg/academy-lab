<?php

namespace Database\Seeders;

use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tracks = [
            [
                'name' => 'ADVPL Essencial',
                'description' => 'Trilha essencial para desenvolvedores ADVPL iniciantes e intermediários.',
            ],
            [
                'name' => 'Consultor Essencial',
                'description' => 'Trilha para formação de consultores Protheus completos.',
            ],
            [
                'name' => 'Banco de Dados',
                'description' => 'Trilha focada em banco de dados e otimização de consultas.',
            ],
            [
                'name' => 'Avançado',
                'description' => 'Trilha para desenvolvedores avançados com tópicos complexos.',
            ],
        ];

        foreach ($tracks as $track) {
            Track::create($track);
        }
    }
}
