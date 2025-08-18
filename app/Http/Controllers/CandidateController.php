<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = Candidate::with('election')->orderBy('nombre')->get();
        return view('candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $elections = Election::where('is_active', true)->get();
        return view('candidates.create', compact('elections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'nombre' => 'required|string|max:255',
            'sigla' => 'required|string|max:50',
            'color_hex' => 'required|string|max:7'
        ]);

        Candidate::create($request->all());

        return redirect()->route('candidates.index')
            ->with('success', 'Candidato creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        $elections = Election::where('is_active', true)->get();
        return view('candidates.edit', compact('candidate', 'elections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,id',
            'nombre' => 'required|string|max:255',
            'sigla' => 'required|string|max:50',
            'color_hex' => 'required|string|max:7'
        ]);

        $candidate->update($request->all());

        return redirect()->route('candidates.index')
            ->with('success', 'Candidato actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->route('candidates.index')
            ->with('success', 'Candidato eliminado exitosamente.');
    }
}
