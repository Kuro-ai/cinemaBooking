<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'movie_id',
        'theatre_id',
        'date',         
        'start_time',
        'is_active',
    ];
    

    /**
     * Relationship with Movie.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Relationship with Theatre.
     */
    public function theatre()
    {
        return $this->belongsTo(Theatre::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'schedule_seat')
                    ->withPivot('is_available')
                    ->withTimestamps();
    }
}
