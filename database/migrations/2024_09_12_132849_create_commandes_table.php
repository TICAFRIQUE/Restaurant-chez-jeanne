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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->integer('nombre_produit')->nullable();
            $table->enum('statut' , ['en attente' ,'confirmée' , 'livrée' , 'annulée'])->nullable();
            $table->enum('mode_livraison' , ['yango' , 'recuperer'])->nullable();
            $table->longText('adresse_livraison')->nullable();
            $table->double('montant_total')->nullable();
            $table->date('date_commande')->nullable();


            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};