<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class driver extends Model
{
    protected $table='driver';
    use HasFactory;
    protected $fillable = [
        'nama',
        'user_id',
       ];

}
