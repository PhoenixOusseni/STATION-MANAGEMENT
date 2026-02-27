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
        Schema::create('pistolets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pompe_id')->constrained()->onDelete('cascade');
            $table->foreignId('pompiste_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nom');
            $table->string('numero')->unique();
            $table->enum('etat', ['actif', 'inactif', 'maintenance'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pistolets');
    }
};
