<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Administrador',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'mesa_number' => 0, // El admin no tiene mesa asignada
            'role' => 'admin',
            'is_active' => true
        ]);

        // 16 usuarios con mesas asignadas (mesas 1-16)
        $users = [
            ['name' => 'Franco Avaro', 'username' => 'mesa001', 'mesa_number' => 1],
            ['name' => 'Ian Romero', 'username' => 'mesa002', 'mesa_number' => 2],
            ['name' => 'Kathleen Barrientos', 'username' => 'mesa003', 'mesa_number' => 3],
            ['name' => 'Luis Mercado', 'username' => 'mesa004', 'mesa_number' => 4],
            ['name' => 'Santiago Tardio', 'username' => 'mesa005', 'mesa_number' => 5],
            ['name' => 'Rodrigo Eguez', 'username' => 'mesa006', 'mesa_number' => 6],
            ['name' => 'Roberto Rodriguez', 'username' => 'mesa007', 'mesa_number' => 7],
            ['name' => 'Didier Flores', 'username' => 'mesa008', 'mesa_number' => 8],
            ['name' => 'Bruno Marco', 'username' => 'mesa009', 'mesa_number' => 9],
            ['name' => 'Andres Flores', 'username' => 'mesa010', 'mesa_number' => 10],
            ['name' => 'Said Bacotich', 'username' => 'mesa011', 'mesa_number' => 11],
            ['name' => 'Danna Gomez', 'username' => 'mesa012', 'mesa_number' => 12],
            ['name' => 'Santiago Camacho', 'username' => 'mesa013', 'mesa_number' => 13],
            ['name' => 'Andre Romero', 'username' => 'mesa014', 'mesa_number' => 14],
            ['name' => 'Santiago Rivero', 'username' => 'mesa015', 'mesa_number' => 15],
            ['name' => 'Sergio Iporre', 'username' => 'mesa016', 'mesa_number' => 16],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'password' => Hash::make('123456'), // Contraseña por defecto
                'mesa_number' => $userData['mesa_number'],
                'role' => 'user',
                'is_active' => true
            ]);
        }
    }
}
