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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tienda_id'); // Referencia a la tienda
            $table->string('perfil')->nullable();
            $table->string('portada')->nullable();
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
        Schema::dropIfExists('images');
    }
};
