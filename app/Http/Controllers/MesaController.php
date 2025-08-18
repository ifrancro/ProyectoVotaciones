<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = Mesa::with('users')->orderBy('codigo')->get();
        return view('mesas.index', compact('mesas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mesas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:mesas',
            'recinto' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255'
        ]);

        Mesa::create($request->all());

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        return view('mesas.show', compact('mesa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([
            'codigo' => 'required|string|max:255|unique:mesas,codigo,' . $mesa->id,
            'recinto' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255'
        ]);

        $mesa->update($request->all());

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada exitosamente.');
    }
}
