<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    protected $table = 'chatbot_messages';

    protected $fillable = [
        'user_id',
        'role',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
