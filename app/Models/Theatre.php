<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theatre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cinema_id',
        'type',
        'is_active',
        'screen_size',
        'image_path',
    ];

    /**
     * Relationship with Cinema.
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    /**
     * Relationship with Schedules.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Relationship with Seats.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

     /**
     * Relationship with Movies.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'schedules')
                    ->withPivot(['start_time', 'end_time'])
                    ->withTimestamps();
    }

}
