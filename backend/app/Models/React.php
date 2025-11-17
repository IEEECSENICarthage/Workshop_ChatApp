<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class React extends Model
{
    /** @use HasFactory<\Database\Factories\ReactFactory> */
    use HasFactory;
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_reacts');
    }
}
