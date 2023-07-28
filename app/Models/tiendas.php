<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tiendas extends Model
{
    use HasFactory;

        protected $fillable = ['nombreP', 'cedula', 'email', 'password', 'nombreN','registro' ,'ubicacion','telefono' ,
        'imagen'];


    
}
