<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $table = 'ai_settings';

    protected $fillable = [
        'assistant_name',
        'welcome_message',
        'system_prompt',
        'model',
    ];
}
