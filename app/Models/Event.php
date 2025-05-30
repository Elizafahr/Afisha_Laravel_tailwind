<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'category',
        'start_datetime',
        'end_datetime',
        'location',
        'age_restriction',
        'poster_url',
        'is_published'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Отношения
    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }


  // Проверка, есть ли бесплатные билеты
  public function hasFreeTickets()
  {
      return $this->tickets()->where('price', 0)->exists();
  }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'event_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'event_id', 'event_id');
    }

    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class, 'event_id', 'event_id');
    // }
    // Добавьте этот метод в класс Event
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'event_id', 'event_id');
    }

    public function getIsFavoriteAttribute()
    {
        if (auth()->check()) {
            return $this->favorites()->where('user_id', auth()->id())->exists();
        }
        return false;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'event_id', 'event_id');
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Ticket::class, 'event_id', 'ticket_id', 'event_id', 'ticket_id');
    }

    // Scope-ы
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now());
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFree($query)
    {
        return $query->whereHas('tickets', function($q) {
            $q->where('price', 0);
        });
    }
  

    public function isSeated()
    {
        return $this->booking_type === 'seated';
    }
    // Атрибуты
    public function getIsFreeAttribute()
    {
        return $this->tickets()->where('price', 0)->exists();
    }

    public function getPosterUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default-event.jpg');
    }

    public function getDurationAttribute()
    {
        return Carbon::parse($this->start_datetime)->diffForHumans($this->end_datetime, true);
    }

}
