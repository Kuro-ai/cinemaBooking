<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'seat_id',
        'price',
        'status',
        'issued_at',
    ];

    /**
     * Relationship with Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship with Seat.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
