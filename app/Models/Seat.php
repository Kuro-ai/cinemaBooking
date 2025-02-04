<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'theatre_id',
        'seat_number',
        'type',
        'price',
    ];

    /**
     * Relationship with Theatre.
     */
    public function theatre()
    {
        return $this->belongsTo(Theatre::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_seat')
                    ->withPivot('is_available')
                    ->withTimestamps();
    }
}
