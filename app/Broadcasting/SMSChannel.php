<?php

namespace App\Broadcasting;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        Http::fake(function ($request) use ($notification) {
            return Http::response(json_encode(['status' => true]), 200, [
                'Content-Type' => 'application/json',
            ]);
        });

        $response = Http::get('https://sms.com', [
            'title' => $notification->title,
        ]);

        Log::info($response->body());
    }
}
