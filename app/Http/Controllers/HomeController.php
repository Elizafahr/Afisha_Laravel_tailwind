<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Venue;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем уникальные категории из событий
        $categories = Event::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->get()
            ->pluck('category');

        // Получаем данные для главной страницы
        $upcomingEvents = Event::query()
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->limit(8)
            ->get();

        $todayEvents = Event::query()
            ->whereDate('start_datetime', today())
            ->orderBy('start_datetime')
            ->limit(5)
            ->get();

        $freeEvents = Event::query()
            ->where('is_free', true)
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->limit(5)
            ->get();

        $featuredEvents = Event::query()
            ->where('start_datetime', '>', now())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $news = News::query()
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact(
            'upcomingEvents',
            'todayEvents',
            'freeEvents',
            'featuredEvents',
            'news',
            'categories'
        ));
    }

    public function event()
    {
        return view('events.event');

    }
}
