<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'Bookings';
    protected $primaryKey = 'booking_id'; // Указываем первичный ключ
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        // 'booking_code'
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
public function seats()
{
    return $this->hasMany(Seat::class, 'booking_id');
}
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
    // В модели Booking
    protected static function booted()
    {
        static::creating(function ($booking) {
            // Автоматически устанавливаем event_id, если не задан
            if (empty($booking->event_id)) {
                if ($booking->ticket) {
                    $booking->event_id = $booking->ticket->event_id;
                } elseif ($booking->seat) {
                    $booking->event_id = $booking->seat->event_id;
                }
            }

            // Генерация уникального кода бронирования
            // if (empty($booking->booking_code)) {
            //     $booking->booking_code = strtoupper(Str::random(8));
            // }
        });
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
