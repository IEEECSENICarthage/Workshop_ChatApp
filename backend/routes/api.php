<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ReactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/reacts", [ReactController::class, "getAll"]);
Route::post("AddReactToChat", [ReactController::class, "AddReactToChat"]);
Route::get("/chats", [ChatController::class, "getAllChat"]);
Route::post("/AddMessage", [ChatController::class, "AddMessage"]);
Route::delete("/DeleteChat/{id}", [ChatController::class, "DeleteChat"]);
Route::post("/AddLike", [ConfigController::class, "AddLike"]);
Route::get("/Likes", [ConfigController::class, "GetAllLikes"]);
