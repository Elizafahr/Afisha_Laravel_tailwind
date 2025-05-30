<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'event_id',
        'ticket_type',
        'price',
        'quantity_available',
        'booking_start',
        'booking_end'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'booking_start' => 'datetime',
        'booking_end' => 'datetime',
    ];

    // Отношения
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ticket_id', 'ticket_id');
    }

    // Scope-ы
    public function scopeAvailable($query)
    {
        return $query->where('quantity_available', '>', 0)
            ->where(function($q) {
                $q->whereNull('booking_start')
                  ->orWhere('booking_start', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('booking_end')
                  ->orWhere('booking_end', '>=', now());
            });
    }

    // Методы
    public function isAvailable()
    {
        return $this->quantity_available > 0 &&
               (is_null($this->booking_start) || $this->booking_start <= now()) &&
               (is_null($this->booking_end) || $this->booking_end >= now());
    }
}
