<?php

namespace App\Services;

use App\Models\{Order, Payment};

class PaymentService
{
    public function process(Order $order, array $payload): Payment
    {
        return Payment::create([
            'payment_id' => 'PMT-'.uniqid(),
            'order_id' => $order->id,
            'payment_method' => $payload['payment_method'] ?? 'cash',
            'status' => 'completed',
            'amount' => $payload['amount'] ?? $order->total_amount,
            'currency' => $payload['currency'] ?? 'USD',
            'gateway' => $payload['gateway'] ?? null,
        ]);
    }
}
