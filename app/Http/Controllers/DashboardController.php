<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Obtener la elección activa
        $election = Election::where('is_active', true)->first();
        
        if (!$election) {
            return view('dashboard.index', [
                'user' => $user,
                'election' => null,
                'candidates' => collect(),
                'totalVotes' => 0,
                'lastUpdate' => now()
            ]);
        }

        // Obtener candidatos con sus votos totales
        $candidates = Candidate::where('election_id', $election->id)
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->map(function($candidate) {
                $totalVotes = $candidate->actaVotes()->sum('votes');
                return [
                    'id' => $candidate->id,
                    'nombre' => $candidate->name,
                    'sigla' => $candidate->party,
                    'color_hex' => $candidate->color_hex,
                    'votos' => $totalVotes
                ];
            });

        // Calcular total de votos
        $totalVotes = $candidates->sum('votos');

        // Obtener última actualización
        $lastUpdate = Acta::where('election_id', $election->id)
            ->latest('updated_at')
            ->value('updated_at') ?? now();

        // Verificar progreso de actas registradas
        $actasRegistradas = Acta::where('election_id', $election->id)->count();
        $mesasCompletadas = $actasRegistradas;
        $todasCompletadas = $actasRegistradas >= 16;

        return view('dashboard.index', [
            'user' => $user,
            'election' => $election,
            'candidates' => $candidates,
            'totalVotes' => $totalVotes,
            'lastUpdate' => $lastUpdate,
            'mesasCompletadas' => $mesasCompletadas,
            'todasCompletadas' => $todasCompletadas
        ]);
    }
}
