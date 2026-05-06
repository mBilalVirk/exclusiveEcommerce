<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatService;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService) {}

    public function handle(Request $request)
    {
        try {
            $request->validate([
                'messages' => 'required|array',
                'messages.*.role' => 'required|in:user,assistant',
                'messages.*.content' => 'required|string',
            ]);

            $reply = $this->chatService->processChatMessage($request->messages);

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('ChatController Error', ['message' => $e->getMessage()]);
            return response()->json(['reply' => '❌ Something went wrong. Please try again.'], 500);
        }
    }
}