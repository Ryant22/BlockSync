<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->index();
            $table->string('category');
            $table->string('key');
            $table->unsignedBigInteger('value')->default(0);
            $table->timestamps();
            $table->unique(['uuid', 'category', 'key'], 'stats_unique_key');
            $table->foreign('uuid')->references('uuid')->on('players')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
