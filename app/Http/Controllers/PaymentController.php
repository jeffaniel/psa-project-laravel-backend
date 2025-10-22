<?php

namespace App\Http\Controllers;

use App\Models\{Order, Payment};
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $svc) {}

    public function index() { return response()->json(Payment::latest()->paginate(20)); }

    public function process(Request $request)
    {
        $data = $request->validate(['order_id' => 'required|exists:orders,id','payment_method' => 'required|string']);
        $order = Order::findOrFail($data['order_id']);
        $payment = $this->svc->process($order, $request->all());
        return response()->json($payment, 201);
    }

    public function show(Payment $id) { return response()->json($id); }
    public function stripeWebhook(Request $request) { return response()->json(['message' => 'Stripe webhook received (stub)']); }
    public function paypalWebhook(Request $request) { return response()->json(['message' => 'PayPal webhook received (stub)']); }
    public function refund(Request $request) { return response()->json(['message' => 'Refund processed (stub)']); }
}
