<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name'     => 'Manuel',
            'email'    => 'marmocreativo@gmail.com',
            'password' => bcrypt('Angeles1#'),
        ]);
    }
}
