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
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('propietario');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('negocio');
            $table->string('slogan');
            $table->string('categoria');
            $table->string('nit')->unique();
            $table->string('ubicacion');
            $table->string('telefono');
            $table->string('perfil')->nullable();
            $table->string('portada')->nullable();        
            $table->timestamps();
           

     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiendas');
    }
};
