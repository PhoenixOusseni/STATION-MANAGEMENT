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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->foreignId('pistolet_id')->constrained()->onDelete('restrict');
            $table->foreignId('pompiste_id')->constrained('users')->onDelete('restrict');
            $table->string('numero_vente')->unique();
            $table->decimal('quantite', 12, 2); // Quantité en litres
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 15, 2);
            $table->enum('mode_paiement', ['especes', 'carte', 'mobile_money', 'credit'])->default('especes');
            $table->dateTime('date_vente');
            $table->string('client')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
