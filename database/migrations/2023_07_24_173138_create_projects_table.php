<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->timestamp('start_date')
                ->nullable();
            $table->timestamp('end_date')
                ->nullable();
            $table->json('tags')
                ->nullable();
            $table->json('languages')
                ->nullable();
            $table->json('content')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
