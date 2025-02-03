<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSeat extends Model
{
    use HasFactory;
    protected $table = 'schedule_seat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'seat_id',
        'is_available',
    ];

    /**
     * Relationship with Schedule.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relationship with Seat.
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
