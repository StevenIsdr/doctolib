<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('raison');
            $table->string('details',500);
            $table->string('rapport',2000)->nullable();
            $table->timestamp('date')->nullable();
            $table->unsignedBigInteger('demandeur_id');
            $table->foreign('demandeur_id')->references('id')->on('users');
            $table->unsignedBigInteger('docteur_id');
            $table->foreign('docteur_id')->references('id')->on('users');
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demandes');
    }
}
