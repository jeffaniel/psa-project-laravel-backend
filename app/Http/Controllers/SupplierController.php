<?php

namespace App\Http\Controllers;

use App\Models\{Supplier};
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() { return response()->json(Supplier::paginate(20)); }
    public function store(Request $request) { $s = Supplier::create($request->all()); return response()->json($s, 201); }
    public function show(Supplier $id) { return response()->json($id); }
    public function update(Request $request, Supplier $id) { $id->update($request->all()); return response()->json($id); }
    public function destroy(Supplier $id) { $id->delete(); return response()->json(['message' => 'Deleted']); }
    public function getProducts(Supplier $id) { return response()->json($id->products()->paginate(20)); }
    public function createPurchaseOrder(Supplier $id, Request $request) { return response()->json(['message' => 'PO created (stub)']); }
}
