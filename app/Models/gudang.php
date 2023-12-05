<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudang extends Model
{
    protected $table = 'gudang';
    use HasFactory;
    protected $fillable = [
        'nama',
        'alamat',
       ];
}
