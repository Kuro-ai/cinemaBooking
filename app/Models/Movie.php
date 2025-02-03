<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'genre',
        'director',
        'duration',
        'language',
        'trailer_url',
        'release_date',
        'is_active',
        'image_path',
    ];

    /**
     * Relationship with Schedules.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function theatres()
    {
        return $this->belongsToMany(Theatre::class, 'schedules')
                    ->withPivot(['start_time', 'end_time'])
                    ->withTimestamps();
    }
}
