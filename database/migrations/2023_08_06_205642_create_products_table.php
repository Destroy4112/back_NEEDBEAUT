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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('precio');
            $table->integer('cantidad');
            $table->timestamps();

             // Definir la relación con la tabla de tiendas
             $table->foreign('tienda_id')->references('id')->on('tiendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
