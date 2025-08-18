<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $elections = Election::orderBy('fecha', 'desc')->get();
        return view('elections.index', compact('elections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('elections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'is_active' => 'boolean'
        ]);

        Election::create($request->all());

        return redirect()->route('elections.index')
            ->with('success', 'Elección creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Election $election)
    {
        return view('elections.show', compact('election'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Election $election)
    {
        return view('elections.edit', compact('election'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Election $election)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'is_active' => 'boolean'
        ]);

        $election->update($request->all());

        return redirect()->route('elections.index')
            ->with('success', 'Elección actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Election $election)
    {
        $election->delete();

        return redirect()->route('elections.index')
            ->with('success', 'Elección eliminada exitosamente.');
    }
}
