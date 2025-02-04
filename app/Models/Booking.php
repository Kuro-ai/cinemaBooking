<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'schedule_id',
        'booking_code',
        'total_seats',
        'total_price',
        'payment_type',
        'payment_date',
        'status',
        'seat_numbers',
    ];

    protected $casts = [
        'seat_numbers' => 'array',
    ];
    

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Schedule.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function seats()
    {
        return $this->hasManyThrough(Seat::class, ScheduleSeat::class, 'schedule_id', 'id', 'schedule_id', 'seat_id');
    }

    public function theatre()
    {
        return $this->schedule->theatre();
    }

    public function movie()
    {
        return $this->schedule->movie();  
    }

}
