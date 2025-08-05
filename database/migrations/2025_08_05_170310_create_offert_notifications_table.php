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
        Schema::create('offert_notifications', function (Blueprint $table) {
            $table->id();


            $table->foreignId('offert_id')
                ->nullable()
                ->constrained('offerts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('vente_id') //
                ->nullable()
                ->constrained('ventes')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->text('message');
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offert_notifications');
    }
};
