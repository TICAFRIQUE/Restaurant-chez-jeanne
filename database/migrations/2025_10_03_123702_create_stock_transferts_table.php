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
        Schema::create('stock_transferts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();

            $table->foreignId('from_produit_id') // produit source
                ->nullable()
                ->constrained('produits')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreignId('to_produit_id') // produit destination
                ->nullable()
                ->constrained('produits')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Quantité transférée
            $table->integer('quantite_bouteille')->nullable(); // quantité en bouteille
            $table->integer('quantite_verre')->nullable(); // quantité de verre qui peut être dans une bouteille
            $table->integer('quantite_total')->nullable(); // quantité totale en unité de sortie


            // Utilisateur qui a fait le transfert
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->timestamp('date_transfert')->nullable();
            // Infos supplémentaires
            $table->text('commentaire')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transferts');
    }
};
