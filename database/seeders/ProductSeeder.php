<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'eduzz_id' => '1001',
                'name' => 'Formação ADVPL Completa',
                'slug' => 'formacao-advpl-completa',
                'description' => 'Curso completo de ADVPL desde o básico até o avançado. Aprenda a desenvolver sistemas completos para Protheus.',
                'redirect_url' => 'https://example.com/advpl',
                'featured' => true,
                'position' => 1,
                'cover' => null, // Pode adicionar URL de imagem aqui
            ],
            [
                'eduzz_id' => '1002',
                'name' => 'Consultor Protheus Essencial',
                'slug' => 'consultor-protheus-essencial',
                'description' => 'Torne-se um consultor Protheus completo. Aprenda configuração, parametrização e customização.',
                'redirect_url' => 'https://example.com/consultor',
                'featured' => true,
                'position' => 2,
                'cover' => null, // Pode adicionar URL de imagem aqui
            ],
            [
                'eduzz_id' => '1003',
                'name' => 'SQL para Protheus',
                'slug' => 'sql-para-protheus',
                'description' => 'Domine consultas SQL aplicadas ao Protheus. Otimize suas queries e relatórios.',
                'redirect_url' => 'https://example.com/sql',
                'featured' => false,
                'position' => 3,
                'cover' => null, // Pode adicionar URL de imagem aqui
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
