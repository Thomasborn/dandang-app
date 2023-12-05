<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $table ='customer';
    use HasFactory;
    protected $fillable = [
        'kode',
        'nama',
        'nomor_telepon',
        'alamat',
       ];
       public function transactions()
       {
           return $this->hasMany(transaksi::class, 'customer_id');
       }
}
