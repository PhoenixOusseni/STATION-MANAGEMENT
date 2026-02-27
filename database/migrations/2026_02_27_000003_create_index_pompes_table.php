<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('index_pompes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pompe_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_vente_id')->constrained('session_ventes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            // Compteur au démarrage
            $table->decimal('index_depart', 15, 2);
            // Compteur à la clôture (rempli à la fin de session)
            $table->decimal('index_final', 15, 2)->nullable();
            // Quantité vendue selon le compteur = index_final - index_depart
            $table->decimal('quantite_vendue_compteur', 12, 2)->nullable();
            $table->dateTime('date_releve_depart');
            $table->dateTime('date_releve_final')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('index_pompes');
    }
};
