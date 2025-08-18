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
        Schema::create('acta_candidate_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->integer('votes'); // Votos para este candidato
            $table->timestamps();
            
            // Un candidato solo puede tener un registro de votos por acta
            $table->unique(['acta_id', 'candidate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_candidate_votes');
    }
};
