<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'event_id',
        'seat_number',
        'zone',
        'seat_row',
        'is_reserved',
        'price_multiplier'
    ];

    protected $casts = [
        'is_reserved' => 'boolean',
        'price_multiplier' => 'decimal:2',
    ];

    // Отношения
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'seat_id', 'seat_id');
    }

    // Методы
    public function getPriceForEvent(Event $event)
    {
        $basePrice = $event->tickets()->first()->price;
        return $basePrice * $this->price_multiplier;
    }
}
