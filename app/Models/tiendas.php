<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiendas extends Model
{
    use HasFactory;

    protected $fillable = [
        'propietario',
        'email',
        'password',
        'negocio',
        'categoria',
        'nit',
        'ubicacion',
        'telefono',
        'perfil',
        'portada',
    ];
}