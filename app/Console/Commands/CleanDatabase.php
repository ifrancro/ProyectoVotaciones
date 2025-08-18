<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Candidate;
use App\Models\Acta;
use App\Models\ActaCandidateVote;
use App\Models\Election;
use Illuminate\Support\Facades\DB;

class CleanDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar datos basura de la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza de la base de datos...');

        // 1. Eliminar candidatos duplicados
        $this->info('1. Eliminando candidatos duplicados...');
        $duplicates = DB::table('candidates')
            ->select('name', 'party', DB::raw('COUNT(*) as count'))
            ->groupBy('name', 'party')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $candidates = Candidate::where('name', $duplicate->name)
                ->where('party', $duplicate->party)
                ->orderBy('id')
                ->get();
            
            // Mantener el primero, eliminar los demás
            for ($i = 1; $i < count($candidates); $i++) {
                $candidates[$i]->delete();
            }
        }

        // 2. Eliminar actas sin votos válidos
        $this->info('2. Eliminando actas sin votos válidos...');
        $invalidActas = Acta::where('total_votes', 0)
            ->orWhereNull('total_votes')
            ->get();
        
        foreach ($invalidActas as $acta) {
            $acta->delete();
        }

        // 3. Eliminar votos de candidatos con valores NULL o 0
        $this->info('3. Eliminando votos inválidos...');
        ActaCandidateVote::whereNull('votes')
            ->orWhere('votes', 0)
            ->delete();

        // 4. Eliminar candidatos sin votos
        $this->info('4. Eliminando candidatos sin votos...');
        $candidatesWithoutVotes = Candidate::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('acta_candidate_votes')
                  ->whereRaw('acta_candidate_votes.candidate_id = candidates.id');
        })->get();

        foreach ($candidatesWithoutVotes as $candidate) {
            $candidate->delete();
        }

        // 5. Eliminar elecciones sin candidatos
        $this->info('5. Eliminando elecciones sin candidatos...');
        $electionsWithoutCandidates = Election::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('candidates')
                  ->whereRaw('candidates.election_id = elections.id');
        })->get();

        foreach ($electionsWithoutCandidates as $election) {
            $election->delete();
        }

        // 6. Recrear datos limpios
        $this->info('6. Recreando datos limpios...');
        
        // Crear elección limpia
        $election = Election::create([
            'name' => 'Elecciones Generales 2024',
            'description' => 'Elecciones para presidente y vicepresidente',
            'election_date' => '2024-12-15',
            'is_active' => true
        ]);

        // Crear candidatos limpios
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

        $this->info('✅ Limpieza completada exitosamente!');
        $this->info('📊 Estadísticas:');
        $this->info('   - Candidatos: ' . Candidate::count());
        $this->info('   - Actas: ' . Acta::count());
        $this->info('   - Votos registrados: ' . ActaCandidateVote::count());
    }
}
