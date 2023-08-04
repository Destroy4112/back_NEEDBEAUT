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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

          // Insertar categorías preestablecidas
          $categorias = ['Peluquerias', 'Barberias', 'cuidado facial y corporal', 'Ropa', 'Accesrios', 'Articulos de belleza'];

          foreach ($categorias as $categoria) {
              DB::table('categorias')->insert([
                  'nombre' => $categoria,
              ]);
          }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
