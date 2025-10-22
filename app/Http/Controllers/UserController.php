<?php

namespace App\Http\Controllers;

use App\Models\{User, Role};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { return response()->json(User::with('roles')->paginate(20)); }
    public function store(Request $request) { $u = User::create($request->all()); return response()->json($u, 201); }
    public function show(User $id) { return response()->json($id->load('roles')); }
    public function update(Request $request, User $id) { $id->update($request->all()); return response()->json($id->load('roles')); }
    public function destroy(User $id) { $id->delete(); return response()->json(['message' => 'Deleted']); }
    public function assignRole($id, Request $request) { $user = User::findOrFail($id); $role = Role::firstOrCreate(['name' => $request->input('role','staff')], ['display_name' => $request->input('role','staff')]); $user->roles()->syncWithoutDetaching([$role->id]); return response()->json($user->load('roles')); }
}
