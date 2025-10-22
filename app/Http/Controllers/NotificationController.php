<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $svc) {}

    public function index() { return response()->json(['notifications' => []]); }
    public function markAsRead($id) { return response()->json(['message' => 'Marked as read (stub)']); }
    public function markAllAsRead() { return response()->json(['message' => 'All marked as read (stub)']); }
    public function destroy($id) { return response()->json(['message' => 'Deleted (stub)']); }
    public function send(Request $request) { $count = $this->svc->send($request->input('users', []), $request->input('title',''), $request->input('message','')); return response()->json(['sent' => $count]); }
}
