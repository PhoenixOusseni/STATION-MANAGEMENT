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
        Schema::table('ventes', function (Blueprint $table) {
            $table->decimal('index_depart', 12, 2)->nullable()->after('session_vente_id');
            $table->decimal('index_final', 12, 2)->nullable()->after('index_depart');
            $table->decimal('retour_cuve', 12, 2)->nullable()->default(0)->after('index_final');
            $table->decimal('quantite_vendue', 12, 2)->nullable()->after('retour_cuve');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn(['index_depart', 'index_final', 'retour_cuve', 'quantite_vendue']);
        });
    }
};
