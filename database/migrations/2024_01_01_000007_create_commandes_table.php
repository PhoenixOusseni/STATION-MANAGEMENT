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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->foreignId('carburant_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('numero_commande')->unique();
            $table->decimal('quantite', 12, 2); // Quantité en litres
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 15, 2);
            $table->string('fournisseur');
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->date('date_commande');
            $table->date('date_livraison_prevue')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
