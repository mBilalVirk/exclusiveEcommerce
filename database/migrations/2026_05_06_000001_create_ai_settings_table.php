<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('assistant_name')->default('AI Assistant');
            $table->text('welcome_message')->nullable();
            $table->longText('system_prompt')->nullable();
            $table->string('model')->default('openai/gpt-4o-mini');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
