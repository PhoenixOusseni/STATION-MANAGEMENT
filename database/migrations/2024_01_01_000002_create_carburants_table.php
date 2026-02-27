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
        Schema::create('carburants', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Super 91, Gazoil
            $table->string('code')->unique(); // S91, GAZ
            $table->decimal('prix_unitaire', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carburants');
    }
};
