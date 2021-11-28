<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $guarded = [];


    public function bookings()
    {
        return $this->hasMany(OrderBooking::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'department_vs_users');
    }
}
