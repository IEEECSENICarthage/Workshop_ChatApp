<?php

namespace App\Http\Controllers;

use App\Models\config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function AddLike()
    {
        $config = Config::first();
        if (!$config) {
            return response()->json(["error" => "Config not found"], 404);
        }
        $config->value += 1;
        $config->save();
        return response()->json(["data" => "Ok"], 200);
    }
    public function GetAllLikes()
    {
        $config = config::first();
        return response()->json(["data" => $config->value], 200);
    }
}
