<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasApiTokens, Notifiable;
    protected $fillable = [
       
            'username',
            'password',
            'email',  
            // 'role',
            'nomor_telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function hasRole($role)
{
    return $this->role === $role;
}
public function depo()
{
    return $this->hasOne(Depo::class);
}

public function sales()
{
    return $this->hasOne(Sales::class);
}

public function driver()
{
    return $this->hasOne(Driver::class);
}
public function customersWithTransactions()
{
    return $this->hasManyThrough(customer::class, transaksi::class, 'sales_id', 'id', 'id', 'customer_id');
}
}
