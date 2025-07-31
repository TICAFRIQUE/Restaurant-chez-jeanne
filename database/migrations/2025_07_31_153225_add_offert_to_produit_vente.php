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
        Schema::table('produit_vente', function (Blueprint $table) {
            //
            $table->boolean('offert')->default(false)->after('total'); // Ajout de la colonne 'offert' avec une valeur par défaut de false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produit_vente', function (Blueprint $table) {
            //
        });
    }
};
