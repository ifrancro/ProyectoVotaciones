<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actas = Acta::with(['election', 'user'])->orderBy('created_at', 'desc')->get();
        return view('actas.index', compact('actas'));
    }

    /**
     * Mostrar formulario de búsqueda de actas
     */
    public function search()
    {
        $elections = Election::where('is_active', true)->get();
        return view('actas.search', compact('elections'));
    }

    /**
     * Realizar búsqueda de actas
     */
    public function searchResults(Request $request)
    {
        $query = Acta::with(['election', 'user', 'candidateVotes.candidate']);

        // Filtros de búsqueda
        if ($request->filled('election_id')) {
            $query->where('election_id', $request->election_id);
        }

        if ($request->filled('mesa_number')) {
            $query->where('mesa_number', $request->mesa_number);
        }

        if ($request->filled('user_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->filled('username')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        $actas = $query->orderBy('created_at', 'desc')->get();
        $elections = Election::where('is_active', true)->get();

        return view('actas.search', compact('actas', 'elections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $election = Election::where('is_active', true)->first();
        
        if (!$election) {
            return redirect()->route('dashboard')
                ->with('error', 'No hay elecciones activas.');
        }
        
        // Verificar si ya existe un acta para esta mesa en esta elección
        $existingActa = Acta::where('election_id', $election->id)
            ->where('mesa_number', Auth::user()->mesa_number)
            ->first();
            
        if ($existingActa) {
            return redirect()->route('dashboard')
                ->with('error', 'Ya has registrado un acta para la Mesa ' . Auth::user()->mesa_number . '. No puedes registrar otro acta.');
        }
        
        $candidates = Candidate::where('election_id', $election->id)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
        
        return view('actas.create', compact('election', 'candidates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'null_votes' => 'required|integer|min:0',
            'blank_votes' => 'required|integer|min:0',
            'observations' => 'nullable|string',
            'candidate_votes' => 'required|array',
            'candidate_votes.*' => 'integer|min:0',
            'acta_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Calcular total de votos
        $totalVotes = $request->null_votes + $request->blank_votes;
        foreach ($request->candidate_votes as $votes) {
            $totalVotes += $votes;
        }

        // Validar que no exceda 240 votos
        if ($totalVotes > 240) {
            return back()->withErrors(['total' => 'El total de votos no puede exceder 240.']);
        }

        // Procesar foto del acta si se subió
        $photoPath = null;
        if ($request->hasFile('acta_photo')) {
            $photoPath = $request->file('acta_photo')->store('actas', 'public');
        }

        // Crear el acta
        $acta = Acta::create([
            'election_id' => $request->election_id,
            'user_id' => Auth::id(),
            'mesa_number' => Auth::user()->mesa_number,
            'total_votes' => $totalVotes,
            'null_votes' => $request->null_votes,
            'blank_votes' => $request->blank_votes,
            'observations' => $request->observations,
            'photo_path' => $photoPath
        ]);

        // Guardar votos por candidato
        foreach ($request->candidate_votes as $candidateId => $votes) {
            if ($votes > 0) {
                $acta->candidateVotes()->create([
                    'candidate_id' => $candidateId,
                    'votes' => $votes
                ]);
            }
        }

        // Verificar si todas las 16 mesas han registrado sus actas
        $totalActas = Acta::where('election_id', $request->election_id)->count();
        $message = 'Acta registrada exitosamente para la Mesa ' . Auth::user()->mesa_number;
        
        if ($totalActas >= 16) {
            $message .= '. ¡Todas las 16 mesas han registrado sus actas! El conteo de votos está completo.';
        }

        return redirect()->route('dashboard')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Acta $acta)
    {
        $acta->load(['election', 'user', 'candidateVotes.candidate']);
        return view('actas.show', compact('acta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acta $acta)
    {
        // Solo el usuario que creó el acta puede editarlo
        if ($acta->user_id !== Auth::id()) {
            return redirect()->route('actas.index')
                ->with('error', 'No tienes permisos para editar este acta.');
        }

        $election = $acta->election;
        $candidates = Candidate::where('election_id', $election->id)
            ->where('is_active', true)
            ->orderBy('position')
            ->get();
        
        return view('actas.edit', compact('acta', 'election', 'candidates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acta $acta)
    {
        // Solo el usuario que creó el acta puede editarlo
        if ($acta->user_id !== Auth::id()) {
            return redirect()->route('actas.index')
                ->with('error', 'No tienes permisos para editar este acta.');
        }

        $request->validate([
            'null_votes' => 'required|integer|min:0',
            'blank_votes' => 'required|integer|min:0',
            'observations' => 'nullable|string',
            'candidate_votes' => 'required|array',
            'candidate_votes.*' => 'integer|min:0',
            'acta_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Calcular total de votos
        $totalVotes = $request->null_votes + $request->blank_votes;
        foreach ($request->candidate_votes as $votes) {
            $totalVotes += $votes;
        }

        // Validar que no exceda 240 votos
        if ($totalVotes > 240) {
            return back()->withErrors(['total' => 'El total de votos no puede exceder 240.']);
        }

        // Procesar foto del acta si se subió
        $photoPath = $acta->photo_path;
        if ($request->hasFile('acta_photo')) {
            // Eliminar foto anterior si existe
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('acta_photo')->store('actas', 'public');
        }

        // Actualizar el acta
        $acta->update([
            'total_votes' => $totalVotes,
            'null_votes' => $request->null_votes,
            'blank_votes' => $request->blank_votes,
            'observations' => $request->observations,
            'photo_path' => $photoPath
        ]);

        // Actualizar votos por candidato
        $acta->candidateVotes()->delete();
        foreach ($request->candidate_votes as $candidateId => $votes) {
            if ($votes > 0) {
                $acta->candidateVotes()->create([
                    'candidate_id' => $candidateId,
                    'votes' => $votes
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Acta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acta $acta)
    {
        // Solo el usuario que creó el acta puede eliminarlo
        if ($acta->user_id !== Auth::id()) {
            return redirect()->route('actas.index')
                ->with('error', 'No tienes permisos para eliminar este acta.');
        }

        // Eliminar foto si existe
        if ($acta->photo_path && Storage::disk('public')->exists($acta->photo_path)) {
            Storage::disk('public')->delete($acta->photo_path);
        }

        $acta->delete();

        return redirect()->route('actas.index')
            ->with('success', 'Acta eliminada exitosamente.');
    }
}
