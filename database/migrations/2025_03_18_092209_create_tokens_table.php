<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tokenType'); // por ejemplo: access, refresh
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
