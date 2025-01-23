<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'status',
        'payment_type', 
    ];

    /**
     * Relationship with Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
