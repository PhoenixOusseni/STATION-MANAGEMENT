<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_session')->unique();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // qui ouvre la session
            $table->enum('statut', ['en_cours', 'cloturee'])->default('en_cours');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_ventes');
    }
};
