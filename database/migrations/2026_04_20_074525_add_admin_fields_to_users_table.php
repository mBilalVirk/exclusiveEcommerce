<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->string('role')->default('user'); // user, admin, super_admin
            //$table->string('phone')->nullable(); 
            
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'address', 'city', 'country', 'last_login_at', 'is_active']);
        });
    }
};