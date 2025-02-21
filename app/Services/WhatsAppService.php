<?php

namespace App\Services;

use App\Models\WhatsAppNotification;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendMessage(User $user, $message)
    {
        $recipient = $user->phone_number;

        try {
             // Ambil nomor HP dari user

            $this->twilio->messages->create(
                "whatsapp:" . $recipient,
                [
                    "from" => env("TWILIO_WHATSAPP_FROM"),
                    "body" => $message
                ]
            );

            // Simpan ke database
            WhatsAppNotification::create([
                'user_id' => $user->id,
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'sent'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());

            WhatsAppNotification::create([
                'user_id' => $user->id,
                'recipient_number' => $recipient,
                'message' => $message,
                'status' => 'failed'
            ]);

            return false;
        }
    }

    public function sendGasAlert($gasLevel)
    {
        $users = User::whereNotNull('phone_number')->get();

        foreach ($users as $user) {
            $message = "⚠️ *Peringatan Gas Berbahaya!* ⚠️\n\n" .
                "🔥 Level Gas: *$gasLevel* ppm\n" .
                "🚨 Segera lakukan tindakan pencegahan!";

            $this->sendMessage($user, $message);
        }
    }
}