<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorias extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];

    public function tiendas()
    {
        return $this->hasMany(tiendas::class, 'categoria_id');
    }
}
