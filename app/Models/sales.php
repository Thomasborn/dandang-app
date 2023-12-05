<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'depo_id',
        'tipe',
        'user_id',
        'kendaraan_id'
       ];
       public function transactions()
    {
        return $this->hasMany(transaksi::class, 'sales_id');
    }
}
