<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
            'user_id',
        'username',
        'email',
        'password_hash',
        'phone',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_date' => 'datetime',
    ];
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    // Отношения
    public function organizer()
    {
        return $this->hasOne(Organizer::class, 'user_id', 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class, 'user_id', 'user_id');
    // }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'user_id');
    }    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }
    public function hasFavorite($eventId)
    {
        return $this->favorites()->where('event_id', $eventId)->exists();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    // Мутатор для пароля
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = bcrypt($value);
    }

    // Проверка ролей
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOrganizer(): bool
    {
        return $this->role === 'organizer' || $this->isAdmin();
    }
}
