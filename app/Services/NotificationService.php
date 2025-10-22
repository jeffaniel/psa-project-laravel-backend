<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function send(array $users, string $title, string $message): int
    {
        return count($users);
    }
}
