<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categorias;

class Publicaciones extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'publicaciones';

    public function categorias()
    {
        return $this->belongsTo(Categorias::class);
    }
}