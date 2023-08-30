<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{

    use HasFactory;
    protected $fillable = ['tienda_id', 'destacadas'];

    public function Tienda()
    {
        return $this->belongsTo(Tiendas::class);
    }
}
