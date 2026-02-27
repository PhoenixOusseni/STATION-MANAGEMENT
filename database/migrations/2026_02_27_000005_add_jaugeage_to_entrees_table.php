<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            // Mesure physique de la cuve avant le dépotage (champ direct pour performance)
            $table->decimal('quantite_jaugee_avant', 12, 2)->nullable()->after('commande_id');
            $table->text('observation_jaugeage')->nullable()->after('quantite_jaugee_avant');
            // FK vers le jaugeage créé automatiquement lors de l'enregistrement
            $table->foreignId('jaugeage_id')
                ->nullable()
                ->after('observation_jaugeage')
                ->constrained('jaugeages')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->dropForeign(['jaugeage_id']);
            $table->dropColumn(['quantite_jaugee_avant', 'observation_jaugeage', 'jaugeage_id']);
        });
    }
};
