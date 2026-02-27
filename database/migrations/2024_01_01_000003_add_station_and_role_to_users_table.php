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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('station_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'gestionnaire', 'pompiste'])->default('pompiste')->after('password');
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['station_id']);
            $table->dropColumn(['station_id', 'role', 'statut']);
        });
    }
};
