<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index() { return response()->json(['message' => 'Sales list (stub)']); }
    public function store(Request $request) { return response()->json(['message' => 'Sale created (stub)'], 201); }
    public function show($id) { return response()->json(['message' => 'Sale detail (stub)']); }
    public function update(Request $request, $id) { return response()->json(['message' => 'Sale updated (stub)']); }
    public function destroy($id) { return response()->json(['message' => 'Sale deleted (stub)']); }
    public function analytics() { return response()->json(['message' => 'Sales analytics (stub)']); }
    public function trends() { return response()->json(['message' => 'Sales trends (stub)']); }
}
