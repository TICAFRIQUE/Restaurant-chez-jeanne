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
        Schema::table('inventaire_produit', function (Blueprint $table) {
            //
            $table->json('stock_physique_json')->after('stock_physique')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaire_produit', function (Blueprint $table) {
            //
            $table->dropColumn('stock_physique_json');
        });
    }
};
