<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Módulos para ADVPL Básico
        $advplBasico = Course::where('slug', 'advpl-basico')->first();
        if ($advplBasico) {
            $modules = [
                [
                    'course_id' => $advplBasico->id,
                    'name' => 'Introdução ao ADVPL',
                    'description' => 'Conhecendo a linguagem ADVPL e o ambiente de desenvolvimento.',
                    'position' => 1,
                    'duration' => 3600,
                ],
                [
                    'course_id' => $advplBasico->id,
                    'name' => 'Variáveis e Tipos de Dados',
                    'description' => 'Trabalhando com diferentes tipos de dados em ADVPL.',
                    'position' => 2,
                    'duration' => 3600,
                ],
                [
                    'course_id' => $advplBasico->id,
                    'name' => 'Estruturas de Controle',
                    'description' => 'If, While, For e outras estruturas de controle.',
                    'position' => 3,
                    'duration' => 3600,
                ],
                [
                    'course_id' => $advplBasico->id,
                    'name' => 'Funções e Procedures',
                    'description' => 'Criando e utilizando funções reutilizáveis.',
                    'position' => 4,
                    'duration' => 3600,
                ],
            ];

            foreach ($modules as $module) {
                Module::create($module);
            }
        }

        // Módulos para ADVPL Intermediário
        $advplIntermediario = Course::where('slug', 'advpl-intermediario')->first();
        if ($advplIntermediario) {
            $modules = [
                [
                    'course_id' => $advplIntermediario->id,
                    'name' => 'Manipulação de Arquivos',
                    'description' => 'Trabalhando com arquivos DBF e SQL.',
                    'position' => 1,
                    'duration' => 5400,
                ],
                [
                    'course_id' => $advplIntermediario->id,
                    'name' => 'Arrays e Estruturas de Dados',
                    'description' => 'Dominando arrays multidimensionais e estruturas complexas.',
                    'position' => 2,
                    'duration' => 5400,
                ],
                [
                    'course_id' => $advplIntermediario->id,
                    'name' => 'Consultas SQL Avançadas',
                    'description' => 'Queries complexas e otimização de consultas.',
                    'position' => 3,
                    'duration' => 5400,
                ],
                [
                    'course_id' => $advplIntermediario->id,
                    'name' => 'Validações e Tratamento de Erros',
                    'description' => 'Implementando validações robustas e tratamento de exceções.',
                    'position' => 4,
                    'duration' => 5400,
                ],
            ];

            foreach ($modules as $module) {
                Module::create($module);
            }
        }

        // Módulos para MVC no Protheus
        $mvcProtheus = Course::where('slug', 'mvc-protheus')->first();
        if ($mvcProtheus) {
            $modules = [
                [
                    'course_id' => $mvcProtheus->id,
                    'name' => 'Conceitos de MVC',
                    'description' => 'Entendendo o padrão MVC e sua aplicação no Protheus.',
                    'position' => 1,
                    'duration' => 6300,
                ],
                [
                    'course_id' => $mvcProtheus->id,
                    'name' => 'Model - Regras de Negócio',
                    'description' => 'Criando modelos de dados e regras de negócio.',
                    'position' => 2,
                    'duration' => 6300,
                ],
                [
                    'course_id' => $mvcProtheus->id,
                    'name' => 'View - Interface com Usuário',
                    'description' => 'Desenvolvendo interfaces modernas e responsivas.',
                    'position' => 3,
                    'duration' => 6300,
                ],
                [
                    'course_id' => $mvcProtheus->id,
                    'name' => 'Controller - Controle de Fluxo',
                    'description' => 'Implementando controllers eficientes.',
                    'position' => 4,
                    'duration' => 6300,
                ],
            ];

            foreach ($modules as $module) {
                Module::create($module);
            }
        }
    }
}
