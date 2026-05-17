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
      Schema::create('options', function (Blueprint $table) {
    $table->id();
    $table->foreignId('question_id')->constrained()->onDelete('cascade'); // Lié à la question désormais
    $table->string('label');
    $table->boolean('is_correct')->default(false); // La fameuse case "Réponse juste"
    $table->integer('votes_count')->default(0); // <--- CETTE LIGNE EST CRUCIALE
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
    
};
