<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Product;
use App\Models\Track;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar produtos, trilhas e cursos
        $formacaoAdvpl = Product::where('slug', 'formacao-advpl-completa')->first();
        $consultorProtheus = Product::where('slug', 'consultor-protheus-essencial')->first();
        $sqlProtheus = Product::where('slug', 'sql-para-protheus')->first();

        $trackAdvplEssencial = Track::where('name', 'ADVPL Essencial')->first();
        $trackConsultorEssencial = Track::where('name', 'Consultor Essencial')->first();
        $trackBancoDados = Track::where('name', 'Banco de Dados')->first();
        $trackAvancado = Track::where('name', 'Avançado')->first();

        $advplBasico = Course::where('slug', 'advpl-basico')->first();
        $advplIntermediario = Course::where('slug', 'advpl-intermediario')->first();
        $advplAvancado = Course::where('slug', 'advpl-avancado')->first();
        $sqlServer = Course::where('slug', 'sql-server-protheus')->first();
        $configuracao = Course::where('slug', 'configuracao-protheus')->first();
        $mvc = Course::where('slug', 'mvc-protheus')->first();
        $webServices = Course::where('slug', 'integracao-web-services')->first();
        $reports = Course::where('slug', 'reports-dashboards')->first();

        // Relacionar Produtos com Trilhas (product_track)
        if ($formacaoAdvpl && $trackAdvplEssencial) {
            DB::table('product_track')->insert([
                'product_id' => $formacaoAdvpl->id,
                'track_id' => $trackAdvplEssencial->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($formacaoAdvpl && $trackAvancado) {
            DB::table('product_track')->insert([
                'product_id' => $formacaoAdvpl->id,
                'track_id' => $trackAvancado->id,
                'position' => 2,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($consultorProtheus && $trackConsultorEssencial) {
            DB::table('product_track')->insert([
                'product_id' => $consultorProtheus->id,
                'track_id' => $trackConsultorEssencial->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($sqlProtheus && $trackBancoDados) {
            DB::table('product_track')->insert([
                'product_id' => $sqlProtheus->id,
                'track_id' => $trackBancoDados->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Relacionar Trilhas com Cursos (track_course)
        if ($trackAdvplEssencial && $advplBasico) {
            DB::table('track_course')->insert([
                'track_id' => $trackAdvplEssencial->id,
                'course_id' => $advplBasico->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackAdvplEssencial && $advplIntermediario) {
            DB::table('track_course')->insert([
                'track_id' => $trackAdvplEssencial->id,
                'course_id' => $advplIntermediario->id,
                'position' => 2,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackConsultorEssencial && $configuracao) {
            DB::table('track_course')->insert([
                'track_id' => $trackConsultorEssencial->id,
                'course_id' => $configuracao->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackConsultorEssencial && $advplBasico) {
            DB::table('track_course')->insert([
                'track_id' => $trackConsultorEssencial->id,
                'course_id' => $advplBasico->id,
                'position' => 2,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackConsultorEssencial && $sqlServer) {
            DB::table('track_course')->insert([
                'track_id' => $trackConsultorEssencial->id,
                'course_id' => $sqlServer->id,
                'position' => 3,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackBancoDados && $sqlServer) {
            DB::table('track_course')->insert([
                'track_id' => $trackBancoDados->id,
                'course_id' => $sqlServer->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackAvancado && $advplAvancado) {
            DB::table('track_course')->insert([
                'track_id' => $trackAvancado->id,
                'course_id' => $advplAvancado->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackAvancado && $mvc) {
            DB::table('track_course')->insert([
                'track_id' => $trackAvancado->id,
                'course_id' => $mvc->id,
                'position' => 2,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($trackAvancado && $webServices) {
            DB::table('track_course')->insert([
                'track_id' => $trackAvancado->id,
                'course_id' => $webServices->id,
                'position' => 3,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Relacionar Produtos diretamente com Cursos (product_course)
        // Exemplo: alguns cursos podem estar no produto sem passar por trilhas
        if ($formacaoAdvpl && $reports) {
            DB::table('product_course')->insert([
                'product_id' => $formacaoAdvpl->id,
                'course_id' => $reports->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($consultorProtheus && $reports) {
            DB::table('product_course')->insert([
                'product_id' => $consultorProtheus->id,
                'course_id' => $reports->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($sqlProtheus && $sqlServer) {
            DB::table('product_course')->insert([
                'product_id' => $sqlProtheus->id,
                'course_id' => $sqlServer->id,
                'position' => 1,
                'visibility' => 'visible',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
