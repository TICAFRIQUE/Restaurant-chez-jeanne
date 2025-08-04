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
        Schema::create('offerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id') // 
                ->nullable()
                ->constrained('produits')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('vente_id') // 
                ->nullable()
                ->constrained('ventes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('variante_id') // 
                ->nullable()
                ->constrained('variantes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('quantite')->default(0); // Quantité de l'offre


            $table->foreignId('user_approuved') // user qui approuve l'offre
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('user_created') // user qui crée l'offre
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp('date_created')->nullable(); // Date de création de l'offre
            $table->timestamp('date_approuved')->nullable(); // Date de création de l'offre

            $table->boolean('offert_statut')->nullable(); // Offre approuvée ou non
            $table->boolean('statut_view')->default(false); // Statut de la vue de l'offre par le le gestionnaire


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerts');
    }
};
