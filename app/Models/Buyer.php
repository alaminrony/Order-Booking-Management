<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $table = 'buyers';


    public function bookings()
    {
        return $this->hasMany(OrderBooking::class);
    }
}
