<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar módulo "Introdução ao ADVPL"
        $moduloIntro = Module::where('name', 'Introdução ao ADVPL')->first();
        if ($moduloIntro) {
            $lessons = [
                [
                    'module_id' => $moduloIntro->id,
                    'name' => 'Bem-vindo ao Curso de ADVPL',
                    'slug' => 'bem-vindo-ao-curso-de-advpl',
                    'description' => 'Apresentação do curso e objetivos de aprendizado.',
                    'resume' => 'Conheça a estrutura do curso e prepare-se para sua jornada em ADVPL.',
                    'duration' => 600, // 10 minutos
                    'position' => 1,
                ],
                [
                    'module_id' => $moduloIntro->id,
                    'name' => 'Configurando o Ambiente',
                    'slug' => 'configurando-o-ambiente',
                    'description' => 'Instalação e configuração do ambiente de desenvolvimento.',
                    'resume' => 'Aprenda a instalar o TDS e configurar seu primeiro projeto.',
                    'duration' => 1200, // 20 minutos
                    'position' => 2,
                ],
                [
                    'module_id' => $moduloIntro->id,
                    'name' => 'Primeiro Programa em ADVPL',
                    'slug' => 'primeiro-programa-em-advpl',
                    'description' => 'Criando seu primeiro programa Hello World em ADVPL.',
                    'resume' => 'Escreva, compile e execute seu primeiro código ADVPL.',
                    'duration' => 900, // 15 minutos
                    'position' => 3,
                ],
                [
                    'module_id' => $moduloIntro->id,
                    'name' => 'Estrutura de um Programa ADVPL',
                    'slug' => 'estrutura-de-um-programa-advpl',
                    'description' => 'Entendendo a estrutura e organização de código ADVPL.',
                    'resume' => 'Conheça os elementos fundamentais de um programa ADVPL.',
                    'duration' => 900, // 15 minutos
                    'position' => 4,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }

        // Buscar módulo "Variáveis e Tipos de Dados"
        $moduloVariaveis = Module::where('name', 'Variáveis e Tipos de Dados')->first();
        if ($moduloVariaveis) {
            $lessons = [
                [
                    'module_id' => $moduloVariaveis->id,
                    'name' => 'Declaração de Variáveis',
                    'slug' => 'declaracao-de-variaveis',
                    'description' => 'Como declarar e inicializar variáveis em ADVPL.',
                    'resume' => 'Aprenda as diferentes formas de declarar variáveis.',
                    'duration' => 720, // 12 minutos
                    'position' => 1,
                ],
                [
                    'module_id' => $moduloVariaveis->id,
                    'name' => 'Tipos de Dados Primitivos',
                    'slug' => 'tipos-de-dados-primitivos',
                    'description' => 'Character, Numeric, Date, Logic e outros tipos.',
                    'resume' => 'Conheça os tipos de dados básicos do ADVPL.',
                    'duration' => 900, // 15 minutos
                    'position' => 2,
                ],
                [
                    'module_id' => $moduloVariaveis->id,
                    'name' => 'Conversão entre Tipos',
                    'slug' => 'conversao-entre-tipos',
                    'description' => 'Funções de conversão: Val(), Str(), CtoD() e outras.',
                    'resume' => 'Domine a conversão entre diferentes tipos de dados.',
                    'duration' => 780, // 13 minutos
                    'position' => 3,
                ],
                [
                    'module_id' => $moduloVariaveis->id,
                    'name' => 'Escopo de Variáveis',
                    'slug' => 'escopo-de-variaveis',
                    'description' => 'Variáveis locais, private, public e static.',
                    'resume' => 'Entenda o escopo e a visibilidade das variáveis.',
                    'duration' => 1200, // 20 minutos
                    'position' => 4,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }

        // Buscar módulo "Manipulação de Arquivos"
        $moduloArquivos = Module::where('name', 'Manipulação de Arquivos')->first();
        if ($moduloArquivos) {
            $lessons = [
                [
                    'module_id' => $moduloArquivos->id,
                    'name' => 'Abrindo e Fechando Arquivos',
                    'slug' => 'abrindo-e-fechando-arquivos',
                    'description' => 'Comandos DbUseArea, DbCloseArea e DbSelectArea.',
                    'resume' => 'Aprenda a abrir e fechar arquivos de forma correta.',
                    'duration' => 1080, // 18 minutos
                    'position' => 1,
                ],
                [
                    'module_id' => $moduloArquivos->id,
                    'name' => 'Navegação em Registros',
                    'slug' => 'navegacao-em-registros',
                    'description' => 'DbSkip, DbGoTop, DbGoBottom e posicionamento.',
                    'resume' => 'Navegue pelos registros de forma eficiente.',
                    'duration' => 1080, // 18 minutos
                    'position' => 2,
                ],
                [
                    'module_id' => $moduloArquivos->id,
                    'name' => 'Inclusão e Exclusão de Registros',
                    'slug' => 'inclusao-e-exclusao-de-registros',
                    'description' => 'RecLock, MsUnlock, DbDelete e manipulação de dados.',
                    'resume' => 'Inclua, altere e exclua registros com segurança.',
                    'duration' => 1440, // 24 minutos
                    'position' => 3,
                ],
                [
                    'module_id' => $moduloArquivos->id,
                    'name' => 'Filtros e Índices',
                    'slug' => 'filtros-e-indices',
                    'description' => 'DbSetFilter, DbSetOrder e otimização de consultas.',
                    'resume' => 'Use filtros e índices para consultas rápidas.',
                    'duration' => 1800, // 30 minutos
                    'position' => 4,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }

        // Buscar módulo "Conceitos de MVC"
        $moduloMVC = Module::where('name', 'Conceitos de MVC')->first();
        if ($moduloMVC) {
            $lessons = [
                [
                    'module_id' => $moduloMVC->id,
                    'name' => 'O que é MVC?',
                    'slug' => 'o-que-e-mvc',
                    'description' => 'Introdução ao padrão arquitetural MVC.',
                    'resume' => 'Entenda os conceitos fundamentais do padrão MVC.',
                    'duration' => 900, // 15 minutos
                    'position' => 1,
                ],
                [
                    'module_id' => $moduloMVC->id,
                    'name' => 'MVC no Protheus',
                    'slug' => 'mvc-no-protheus',
                    'description' => 'Como o Protheus implementa o padrão MVC.',
                    'resume' => 'Conheça a implementação específica do MVC no Protheus.',
                    'duration' => 1500, // 25 minutos
                    'position' => 2,
                ],
                [
                    'module_id' => $moduloMVC->id,
                    'name' => 'Vantagens do MVC',
                    'slug' => 'vantagens-do-mvc',
                    'description' => 'Benefícios de usar MVC em seus projetos.',
                    'resume' => 'Descubra por que usar MVC melhora seu código.',
                    'duration' => 1200, // 20 minutos
                    'position' => 3,
                ],
                [
                    'module_id' => $moduloMVC->id,
                    'name' => 'Planejando uma Aplicação MVC',
                    'slug' => 'planejando-uma-aplicacao-mvc',
                    'description' => 'Como estruturar e planejar seu projeto MVC.',
                    'resume' => 'Aprenda a planejar aplicações MVC eficientes.',
                    'duration' => 2700, // 45 minutos
                    'position' => 4,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }
    }
}
