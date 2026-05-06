<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiSetting;

class AiSettingSeeder extends Seeder
{
    public function run(): void
    {
        AiSetting::firstOrCreate([
            'assistant_name' => 'AI Assistant',
            'welcome_message' => "Hi! I'm your AI assistant 🤖 How can I help you today?",
            'system_prompt' => 'You are a helpful ecommerce assistant. Assist users with product information, order tracking, and general inquiries.',
            'model' => 'openai/gpt-4o-mini'
        ]);
    }
}
