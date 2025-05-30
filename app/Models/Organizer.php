<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $primaryKey = 'organizer_id';

    protected $fillable = [
        'user_id',
        'organization_name',
        'description',
        'logo_url',
        'contact_person',
        'is_verified',
         'name',
        'description',
        'contact_info'
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];
// public function user()
// {
//     return $this->belongsTo(User::class);
// }

// public function events()
// {
//     return $this->hasMany(Event::class, 'organizer_id');
// }
    // Отношения
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id', 'organizer_id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'organizer_id', 'organizer_id');
    }

    // Метод для получения URL логотипа
    public function getLogoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default-logo.png');
    }
}
