<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBookingAttachment extends Model
{
    use HasFactory;

    protected $table = 'order_booking_attatchments';

    protected $guarded = [];


    public function booking()
    {
        return $this->belongsTo(OrderBooking::class, 'order_book_id');
    }
}
