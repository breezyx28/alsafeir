<?php

namespace App\Http\Controllers;

use App\Models\Tailor;
use Illuminate\Http\Request;

class TailorController extends Controller
{
    public function index()
    {
        $tailors = Tailor::all();
        return view('tailors.index', compact('tailors'));
    }

    public function create()
    {
        return view('tailors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'id_number' => 'nullable|string|max:255|unique:tailors,id_number',
        ]);

        Tailor::create($request->all());

        return redirect()->route('tailors.index')->with('success', 'Tailor created successfully.');
    }

    public function show(Tailor $tailor)
    {
        return view('tailors.show', compact('tailor'));
    }

    public function edit(Tailor $tailor)
    {
        return view('tailors.edit', compact('tailor'));
    }

    public function update(Request $request, Tailor $tailor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
            'id_number' => 'nullable|string|max:255|unique:tailors,id_number,' . $tailor->id,
        ]);

        $tailor->update($request->all());

        return redirect()->route('tailors.index')->with('success', 'Tailor updated successfully.');
    }

    public function destroy(Tailor $tailor)
    {
        $tailor->delete();

        return redirect()->route('tailors.index')->with('success', 'Tailor deleted successfully.');
    }
}
