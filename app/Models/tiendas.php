<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class tiendas extends Model
{
    use HasFactory;

    /* protected $fillable = [
        'propietario',
        'email',
        'password',
        'negocio',
        'categoria_id',
        'nit',
        'ubicacion',
        'telefono',
        'perfil',
        'portada',
    ];
*/
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(products::class, 'tienda_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    protected function propietario(): Attribute
    {
        return new Attribute(set: fn ($value) => strtolower($value));
    }
}
