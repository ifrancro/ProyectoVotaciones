<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Election;
use App\Models\Candidate;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear elección de prueba
        $election = Election::create([
            'name' => 'Elecciones Generales 2024',
            'description' => 'Elecciones para presidente y vicepresidente',
            'election_date' => '2024-12-15',
            'is_active' => true
        ]);

        // Crear 8 candidatos de prueba
        $candidates = [
            ['name' => 'Rodrigo Paz', 'party' => 'Partido A', 'color_hex' => '#3BA3BC', 'position' => 1],
            ['name' => 'Tuto Quiroga', 'party' => 'Partido B', 'color_hex' => '#FF0000', 'position' => 2],
            ['name' => 'Samuel Doria Medina', 'party' => 'Partido C', 'color_hex' => '#FFD200', 'position' => 3],
            ['name' => 'Andronico Rodriguez', 'party' => 'Partido D', 'color_hex' => '#008F39', 'position' => 4],
            ['name' => 'Manfred Reyes', 'party' => 'Partido E', 'color_hex' => '#6600A1', 'position' => 5],
            ['name' => 'Jhonny Fernandez', 'party' => 'Partido F', 'color_hex' => '#51D1F6', 'position' => 6],
            ['name' => 'Eduardo del Castillo', 'party' => 'Partido G', 'color_hex' => '#0000FF', 'position' => 7],
            ['name' => 'Pavel Aracena Vargas', 'party' => 'Partido H', 'color_hex' => '#8B0000', 'position' => 8],
        ];

        foreach ($candidates as $candidateData) {
            Candidate::create(array_merge($candidateData, [
                'election_id' => $election->id,
                'is_active' => true
            ]));
        }
    }
}
