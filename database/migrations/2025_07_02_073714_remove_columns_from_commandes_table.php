<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverse the migrations.
     */
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['produit_id', 'quantite', 'tarif_unitaire']);
        });
    }

    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->unsignedBigInteger('produit_id')->nullable();
            $table->integer('quantite')->nullable();
            $table->decimal('tarif_unitaire', 10, 2)->nullable();

            $table->foreign('produit_id')->references('id')->on('produits');
        });
    }

};
