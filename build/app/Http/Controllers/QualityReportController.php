<?php

namespace App\Http\Controllers;

use App\Models\QualityReport;
use App\Models\Tailor;
use Illuminate\Http\Request;

class QualityReportController extends Controller
{
    public function index()
    {
        $qualityReports = QualityReport::with('tailor')->get();
        return view('quality_reports.index', compact('qualityReports'));
    }

    public function create()
    {
        $tailors = Tailor::all();
        return view('quality_reports.create', compact('tailors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'order_item_identifier' => 'required|string|max:255',
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,fixed',
            'reported_by' => 'required|string|max:255',
            'reported_date' => 'required|date',
        ]);

        QualityReport::create($request->all());

        return redirect()->route('quality_reports.index')->with('success', 'Quality report created successfully.');
    }

    public function show(QualityReport $qualityReport)
    {
        return view('quality_reports.show', compact('qualityReport'));
    }

    public function edit(QualityReport $qualityReport)
    {
        $tailors = Tailor::all();
        return view('quality_reports.edit', compact('qualityReport', 'tailors'));
    }

    public function update(Request $request, QualityReport $qualityReport)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'order_item_identifier' => 'required|string|max:255',
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,fixed',
            'reported_by' => 'required|string|max:255',
            'reported_date' => 'required|date',
        ]);

        $qualityReport->update($request->all());

        return redirect()->route('quality_reports.index')->with('success', 'Quality report updated successfully.');
    }

    public function destroy(QualityReport $qualityReport)
    {
        $qualityReport->delete();

        return redirect()->route('quality_reports.index')->with('success', 'Quality report deleted successfully.');
    }
}
