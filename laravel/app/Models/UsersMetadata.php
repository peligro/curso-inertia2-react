<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Estados;
use App\Models\Perfiles;
class UsersMetadata extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table ='users_metadata';

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function estados()
    {
        return $this->belongsTo(Estados::class);
    }
    public function perfiles()
    {
        return $this->belongsTo(Perfiles::class);
    }
}