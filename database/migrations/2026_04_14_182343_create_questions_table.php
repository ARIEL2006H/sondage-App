<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On crée bien la table QUESTIONS ici
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // On lie la question au thème (Poll)
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Le texte de la question
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};