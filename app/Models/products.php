<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tienda()
    {
        return $this->belongsTo(tiendas::class, 'tienda_id');
    }
}
