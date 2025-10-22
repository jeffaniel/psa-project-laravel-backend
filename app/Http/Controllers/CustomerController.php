<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Order};
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() { return response()->json(Customer::paginate(20)); }
    public function store(Request $request) { $c = Customer::create($request->all()); return response()->json($c, 201); }
    public function show(Customer $id) { return response()->json($id); }
    public function update(Request $request, Customer $id) { $id->update($request->all()); return response()->json($id); }
    public function destroy(Customer $id) { $id->delete(); return response()->json(['message' => 'Deleted']); }
    public function getOrders(Customer $id) { return response()->json($id->orders()->with('items')->paginate(20)); }
    public function getAnalytics(Customer $id) { return response()->json(['total_orders' => $id->orders()->count(), 'total_spent' => $id->orders()->sum('total_amount')]); }

    public function profile(Request $request) { return response()->json(Customer::where('user_id', $request->user()->id)->first()); }
    public function updateProfile(Request $request) { $c = Customer::where('user_id', $request->user()->id)->firstOrFail(); $c->update($request->all()); return response()->json($c); }
    public function wishlist() { return response()->json(['items' => []]); }
    public function addToWishlist($productId) { return response()->json(['message' => 'Added (stub)']); }
    public function removeFromWishlist($productId) { return response()->json(['message' => 'Removed (stub)']); }
}
