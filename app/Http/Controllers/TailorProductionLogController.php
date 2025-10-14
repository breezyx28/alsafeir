<?php

namespace App\Http\Controllers;

use App\Models\TailorProductionLog;
use App\Models\Tailor;
use App\Models\PieceRateDefinition;
use Illuminate\Http\Request;

class TailorProductionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productionLogs = TailorProductionLog::with(["tailor", "pieceRateDefinition"])->get();
        return view("tailor_production_logs.index", compact("productionLogs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tailors = Tailor::all();
        $pieceRateDefinitions = PieceRateDefinition::all();
        return view("tailor_production_logs.create", compact("tailors", "pieceRateDefinitions"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tailor_id" => "required|exists:tailors,id",
            "piece_rate_definition_id" => "required|exists:piece_rate_definitions,id",
            "quantity" => "required|integer|min:1",
            "production_date" => "required|date",
            "status" => "required|in:completed,under_review,rejected",
            "notes" => "nullable|string",
        ]);

        TailorProductionLog::create($request->all());

        return redirect()->route("tailor_production_logs.index")->with("success", "Production log created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(TailorProductionLog $tailorProductionLog)
    {
        return view("tailor_production_logs.show", compact("tailorProductionLog"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TailorProductionLog $tailorProductionLog)
    {
        $tailors = Tailor::all();
        $pieceRateDefinitions = PieceRateDefinition::all();
        return view("tailor_production_logs.edit", compact("tailorProductionLog", "tailors", "pieceRateDefinitions"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TailorProductionLog $tailorProductionLog)
    {
        $request->validate([
            "tailor_id" => "required|exists:tailors,id",
            "piece_rate_definition_id" => "required|exists:piece_rate_definitions,id",
            "quantity" => "required|integer|min:1",
            "production_date" => "required|date",
            "status" => "required|in:completed,under_review,rejected",
            "notes" => "nullable|string",
        ]);

        $tailorProductionLog->update($request->all());

        return redirect()->route("tailor_production_logs.index")->with("success", "Production log updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TailorProductionLog $tailorProductionLog)
    {
        $tailorProductionLog->delete();

        return redirect()->route("tailor_production_logs.index")->with("success", "Production log deleted successfully.");
    }
}