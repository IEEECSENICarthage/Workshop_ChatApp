<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    public function reacts()
    {
        return $this->belongsToMany(React::class, 'chat_reacts')->withPivot('nbr');
    }
}
