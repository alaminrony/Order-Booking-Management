<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBooking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'order_bookings';

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'buyer_dept');
    }


    public function attachments()
    {
        return $this->hasMany(OrderBookingAttachment::class, 'order_book_id');
    }

}
