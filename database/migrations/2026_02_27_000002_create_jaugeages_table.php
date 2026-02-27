<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jaugeages', function (Blueprint $table) {
            $table->id();
            $table->string('numero_jaugeage')->unique();
            $table->foreignId('cuve_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('session_vente_id')->nullable()->constrained('session_ventes')->onDelete('set null');
            $table->foreignId('entree_id')->nullable()->constrained('entrees')->onDelete('set null');
            // Type de jaugeage
            $table->enum('type', ['debut_session', 'fin_session', 'avant_depotage'])->default('debut_session');
            // Mesure physique (jauge physique / barrette)
            $table->decimal('quantite_mesuree', 12, 2);
            // Stock théorique calculé par le système au moment du jaugeage
            $table->decimal('quantite_theorique', 12, 2);
            // Écart = mesuree - theorique (positif = surplus, négatif = perte)
            $table->decimal('ecart', 12, 2)->default(0);
            $table->dateTime('date_jaugeage');
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jaugeages');
    }
};
