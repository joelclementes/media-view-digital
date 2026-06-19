<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capturista_portal_internet', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('portal_internet_id')
                ->constrained('portales_internet')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['user_id', 'portal_internet_id'], 'capturista_portal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capturista_portal_internet');
    }
};