<?php

namespace App\Http\Controllers;

use App\Models\ChatReact;
use App\Models\React;
use Illuminate\Http\Request;

class ReactController extends Controller
{
    public function getAll()
    {
        $reacts = React::all();
        return response()->json(["data" => $reacts], 200);
    }

    public function AddReactToChat(Request $request)
    {
        $chat_react = ChatReact::where("chat_id", $request->chat_id)->where("react_id", $request->react_id)->first();
        if ($chat_react) {
            $chat_react->nbr += 1;
            $chat_react->save();
        } else {
            $chat_react_new = new ChatReact();
            $chat_react_new->chat_id = $request->chat_id;
            $chat_react_new->react_id = $request->react_id;
            $chat_react_new->nbr = 1;
            $chat_react_new->save();
        }
        return response()->json(["data" => "Ok"], 200);
    }
}
