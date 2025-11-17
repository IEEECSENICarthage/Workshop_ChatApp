<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\config;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getAllChat()
    {
        $chats = Chat::with("reacts")->get();
        return response()->json(["data" => $chats], 200);
    }
    public function AddMessage(Request $request)
    {
        $secretCode = $request->secret_code;
        $correctSecret = config::first();
        if ($secretCode !== $correctSecret->key) {
            return response()->json(['error' => 'Invalid secret code'], 403);
        }
        $chat = new Chat();
        $chat->username = $request->name;
        $chat->message = $request->message;
        $chat->save();
        return response()->json(["data" => $chat], 200);
    }

    public function DeleteChat($id)
    {
        $chat = Chat::find($id);
        $chat->delete();
        return response()->json(["message" => "ok"], 200);
    }
}
