<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tiendas', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable(); // Campo para la clave foránea
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('tiendas', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            
        });
    }
};
