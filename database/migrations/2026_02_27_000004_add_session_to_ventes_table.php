<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->foreignId('session_vente_id')
                ->nullable()
                ->after('pompiste_id')
                ->constrained('session_ventes')
                ->onDelete('set null');

            // Ajout du numéro de ticket s'il n'existe pas encore
            if (!Schema::hasColumn('ventes', 'numero_ticket')) {
                $table->string('numero_ticket')->nullable()->after('numero_vente');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropForeign(['session_vente_id']);
            $table->dropColumn('session_vente_id');
        });
    }
};
