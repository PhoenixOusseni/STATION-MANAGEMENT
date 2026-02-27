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
        Schema::create('cuves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->foreignId('carburant_id')->constrained()->onDelete('restrict');
            $table->string('nom');
            $table->decimal('capacite_max', 12, 2); // Capacité maximale en litres
            $table->decimal('stock_actuel', 12, 2)->default(0); // Stock actuel en litres
            $table->decimal('stock_min', 12, 2)->default(0); // Seuil d'alerte
            $table->string('numero_serie')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuves');
    }
};
