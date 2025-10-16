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
        Schema::create('funcions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->unsignedBigInteger('pelicula_id');
            $table->unsignedBigInteger('sala_id');
            $table->string('tipo'); // 2D, 3D, IMAX
            $table->double('costo');
            $table->foreign('pelicula_id')->references('id')->on('peliculas');
            $table->foreign('sala_id')->references('id')->on('salas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcions');
    }
};
