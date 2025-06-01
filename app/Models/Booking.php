<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory ;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'seat_id',
        'quantity',
        'total_price',
        'status',
        'payment_method'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    // Статусы бронирования
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Отношения с мягким удалением
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

   public function event()
{
    return $this->belongsTo(Event::class, 'event_id', 'event_id');
}

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
    }

    // Accessors для безопасного получения данных
    public function getEventTitleAttribute()
    {
        if ($this->event) {
            return $this->event->title;
        }

        if ($this->ticket && $this->ticket->event) {
            return $this->ticket->event->title;
        }

        if ($this->seat && $this->seat->event) {
            return $this->seat->event->title;
        }

        return 'Мероприятие удалено';
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Пользователь удален';
    }

    // Scope-ы
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    // Методы
    public function confirm()
    {
        $this->status = self::STATUS_CONFIRMED;
        $this->save();

        if ($this->ticket) {
            $this->ticket->decrement('quantity_available', $this->quantity);
        }

        if ($this->seat) {
            $this->seat->update(['is_reserved' => true]);
        }
    }

    public function cancel()
    {
        $this->status = self::STATUS_CANCELLED;
        $this->save();

        if ($this->ticket) {
            $this->ticket->increment('quantity_available', $this->quantity);
        }

        if ($this->seat) {
            $this->seat->update(['is_reserved' => false]);
        }
    }
}
