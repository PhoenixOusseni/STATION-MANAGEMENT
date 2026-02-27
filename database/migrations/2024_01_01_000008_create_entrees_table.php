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
        Schema::create('entrees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuve_id')->constrained()->onDelete('cascade');
            $table->foreignId('commande_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->string('numero_entree')->unique();
            $table->decimal('quantite', 12, 2); // Quantité en litres
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 15, 2);
            $table->dateTime('date_entree');
            $table->string('numero_bon_livraison')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrees');
    }
};
