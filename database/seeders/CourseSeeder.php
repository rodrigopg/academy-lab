<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'ADVPL Básico',
                'slug' => 'advpl-basico',
                'description' => 'Introdução à linguagem ADVPL. Conceitos fundamentais, variáveis, estruturas de controle e funções básicas.',
                'duration' => 14400, // 4 horas em segundos
            ],
            [
                'name' => 'ADVPL Intermediário',
                'slug' => 'advpl-intermediario',
                'description' => 'Aprofundamento em ADVPL. Manipulação de arquivos, queries SQL, arrays e funções avançadas.',
                'duration' => 21600, // 6 horas
            ],
            [
                'name' => 'ADVPL Avançado',
                'slug' => 'advpl-avancado',
                'description' => 'Técnicas avançadas de ADVPL. Web Services, integração, performance e boas práticas.',
                'duration' => 28800, // 8 horas
            ],
            [
                'name' => 'SQL Server para Protheus',
                'slug' => 'sql-server-protheus',
                'description' => 'Domine SQL Server aplicado ao Protheus. Queries, views, procedures e otimização.',
                'duration' => 18000, // 5 horas
            ],
            [
                'name' => 'Configuração de Protheus',
                'slug' => 'configuracao-protheus',
                'description' => 'Configure o ambiente Protheus. Instalação, parametrização e manutenção.',
                'duration' => 10800, // 3 horas
            ],
            [
                'name' => 'MVC no Protheus',
                'slug' => 'mvc-protheus',
                'description' => 'Desenvolva usando o padrão MVC. Criação de telas modernas e manuteníveis.',
                'duration' => 25200, // 7 horas
            ],
            [
                'name' => 'Integração Web Services',
                'slug' => 'integracao-web-services',
                'description' => 'Integre o Protheus com sistemas externos via Web Services REST e SOAP.',
                'duration' => 21600, // 6 horas
            ],
            [
                'name' => 'Reports e Dashboards',
                'slug' => 'reports-dashboards',
                'description' => 'Crie relatórios e dashboards profissionais no Protheus.',
                'duration' => 14400, // 4 horas
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
