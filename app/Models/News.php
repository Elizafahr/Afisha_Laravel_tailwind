<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'news_id';

    protected $fillable = [
        'organizer_id',
        'title',
        'content',
        'image_url',
        'is_pinned',
        'category'

    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Отношения
    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id', 'organizer_id');
    }

    // Scope-ы
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Атрибуты
    public function getExcerptAttribute($length = 100)
    {
        return str(strip_tags($this->content))->limit($length);
    }

    public function getImageUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : asset('images/default-news.jpg');
    }
}
