<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use Illuminate\Http\Request;

class CanchaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canchas = Cancha::all();
        return view('admin.canchas.index', compact('canchas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.canchas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'activa' => 'boolean',
        ]);

        Cancha::create($request->all());

        return redirect()->route('canchas.index')
            ->with('success', 'Cancha creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cancha $cancha)
    {
        return view('admin.canchas.show', compact('cancha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cancha $cancha)
    {
        return view('admin.canchas.edit', compact('cancha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cancha $cancha)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'activa' => 'boolean',
        ]);

        $cancha->update($request->all());

        return redirect()->route('canchas.index')
            ->with('success', 'Cancha actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cancha $cancha)
    {
        $cancha->delete();

        return redirect()->route('canchas.index')
            ->with('success', 'Cancha eliminada exitosamente');
    }
}
