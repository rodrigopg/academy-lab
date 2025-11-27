<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário member de teste (admin já é criado pela migration)
        User::factory()->create([
            'name' => 'Member Test',
            'email' => 'member@teste.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => 2, // Member role
        ]);

        // Chamar seeders de conteúdo
        $this->call([
            ProductSeeder::class,
            TrackSeeder::class,
            CourseSeeder::class,
            ModuleSeeder::class,
            LessonSeeder::class,
            RelationshipSeeder::class,
            ProductUserSeeder::class, // Vincular member aos produtos
        ]);

        $this->command->info('✅ Seeds executados com sucesso!');
        $this->command->info('📊 Usuários criados:');
        $this->command->info('   - Admin: admin@teste.com / password (criado pela migration)');
        $this->command->info('   - Member: member@teste.com / password');
        $this->command->info('📚 Conteúdo:');
        $this->command->info('   - 3 Produtos');
        $this->command->info('   - 4 Trilhas');
        $this->command->info('   - 8 Cursos');
        $this->command->info('   - 12 Módulos');
        $this->command->info('   - 16+ Aulas');
    }
}
