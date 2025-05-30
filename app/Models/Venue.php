<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $primaryKey = 'venue_id';

    protected $fillable = [
        'name',
        'address',
        'description',
        'capacity',
        'image_url',
        'contact_phone',
        'contact_email'
    ];

    // Отношения
    public function events()
    {
        return $this->hasMany(Event::class, 'location', 'address');
    }

    // Атрибуты
    public function getImageUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default-venue.jpg');
    }

    public function getUpcomingEventsCountAttribute()
    {
        return $this->events()->where('start_datetime', '>', now())->count();
    }
}
