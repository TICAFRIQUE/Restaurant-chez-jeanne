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
            //
            $table->enum('statut_paiement', ['paye', 'impaye'])->nullable();
            $table->double('montant_restant')->nullable(); //montant restant de la vente
            $table->boolean('statut_reglement')->nullable(); // reglement effectué ou non [0: non, 1: oui]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            //
            $table->dropColumn(['statut_paiement', 'montant_restant']);
        });
    }
};
