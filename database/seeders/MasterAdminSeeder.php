<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MasterAdminSeeder extends Seeder
{
    /** Cria/garante o admin master. Rode: php artisan db:seed --class=MasterAdminSeeder */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'paulosergioorfanelli@gmail.com'],
            [
                'name'              => 'Paulo Sergio Orfanelli',
                'role'              => 'admin',
                'password'          => '1234', // o cast "hashed" do model faz o hash
                'email_verified_at' => now(),
            ]
        );
    }
}
