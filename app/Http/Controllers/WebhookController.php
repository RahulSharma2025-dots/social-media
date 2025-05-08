<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePusherWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            Log::info('Pusher Webhook Received:', $payload);

            if (isset($payload['events'])) {
                foreach ($payload['events'] as $event) {
                    if ($event['name'] === 'client-message-sent') {
                        Log::info('Client Message Sent:', $event);
                    }
                }
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Error in WebhookController@handlePusherWebhook: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process webhook'], 500);
        }
    }
}
