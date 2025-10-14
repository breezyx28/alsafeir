<?php

namespace App\Http\Controllers;

use App\Models\PieceRateDefinition;
use Illuminate\Http\Request;

class PieceRateDefinitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pieceRateDefinitions = PieceRateDefinition::all();
        return view('piece_rate_definitions.index', compact('pieceRateDefinitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('piece_rate_definitions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_type' => 'required|string|max:255|unique:piece_rate_definitions,item_type',
            'rate' => 'required|numeric|min:0',
        ]);

        PieceRateDefinition::create($request->all());

        return redirect()->route('piece_rate_definitions.index')->with('success', 'Piece rate definition created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PieceRateDefinition $pieceRateDefinition)
    {
        return view('piece_rate_definitions.show', compact('pieceRateDefinition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PieceRateDefinition $pieceRateDefinition)
    {
        return view('piece_rate_definitions.edit', compact('pieceRateDefinition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PieceRateDefinition $pieceRateDefinition)
    {
        $request->validate([
            'item_type' => 'required|string|max:255|unique:piece_rate_definitions,item_type,' . $pieceRateDefinition->id,
            'rate' => 'required|numeric|min:0',
        ]);

        $pieceRateDefinition->update($request->all());

        return redirect()->route('piece_rate_definitions.index')->with('success', 'Piece rate definition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PieceRateDefinition $pieceRateDefinition)
    {
        $pieceRateDefinition->delete();

        return redirect()->route('piece_rate_definitions.index')->with('success', 'Piece rate definition deleted successfully.');
    }
}
