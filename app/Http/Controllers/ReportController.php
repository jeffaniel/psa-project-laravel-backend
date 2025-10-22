<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $svc) {}

    public function salesReport(Request $request) { return response()->json($this->svc->sales($request->all())); }
    public function inventoryReport(Request $request) { return response()->json($this->svc->inventory($request->all())); }
    public function customerReport() { return response()->json(['message' => 'Customer report (stub)']); }
    public function productReport() { return response()->json(['message' => 'Product report (stub)']); }
    public function financialReport() { return response()->json(['message' => 'Financial report (stub)']); }
    public function exportReport($type) { return response()->json(['message' => "Exported $type report (stub)"]); }
}
