<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed do banco de dados.
     */
    public function run(): void
    {
        $this->call([
            DiscQuestionsSeeder::class,
            MasterAdminSeeder::class,
        ]);
    }
}
