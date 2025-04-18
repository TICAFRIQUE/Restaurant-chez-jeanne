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
        Schema::create('inventaire_produit', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventaire_id')
                ->nullable()
                ->constrained('inventaires')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('produit_id')
                ->nullable()
                ->constrained('produits')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // $table->double('stock_global')->nullable(); // stock net
            $table->double('stock_dernier_inventaire')->default(0); // stock total
            $table->double('stock_initial')->default(0);
            $table->double('stock_vendu')->default(0);
            $table->double('stock_theorique')->default(0); //stock theorique
            $table->double('stock_physique')->default(0); // stock disponible
            $table->double('ecart')->nullable();
            $table->string('etat')->nullable(); //en rupture , en stock , endommagé, Stock critique 
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaire_produit');
    }
};
