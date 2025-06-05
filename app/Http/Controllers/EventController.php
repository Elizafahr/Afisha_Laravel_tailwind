<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Основной запрос
        $query = Event::query()->where('start_datetime', '>', now());

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date')) {        // Фильтр по дате
            switch ($request->date) {
                case 'today':
                    $query->whereDate('start_datetime', today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_datetime', today()->addDay());
                    break;
                case 'weekend':
                    $query->whereBetween('start_datetime', [
                        now()->next('Friday')->startOfDay(),
                        now()->next('Sunday')->endOfDay()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('start_datetime', now()->month);
                    break;
            }
        }
        if ($request->filled('price')) {        // Фильтр по цене
            switch ($request->price) {
                case 'free':
                    $query->where('is_free', true);
                    break;
                case '0-500':
                    $query->whereBetween('price', [0, 500]);
                    break;
                case '500-1000':
                    $query->whereBetween('price', [500, 1000]);
                    break;
                case '1000-2000':
                    $query->whereBetween('price', [1000, 2000]);
                    break;
                case '2000+':
                    $query->where('price', '>=', 2000);
                    break;
            }
        }
        if ($request->filled('featured')) {        // Фильтр по избранному (featured)
            $query->where('is_featured', true);
        }
        // Получаем уникальные категории для фильтра
        $categories = Event::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        // Пагинация с сохранением параметров
        $events = $query->orderBy('start_datetime')
            ->paginate(12)
            ->appends(request()->query());
        // Статистика
        $todayCount = Event::whereDate('start_datetime', today())->count();
        $freeCount = Event::where('is_free', true)
            ->where('start_datetime', '>', now())->count();
        $featuredCount = Event::where('is_featured', true)
            ->where('start_datetime', '>', now())->count();
        $featuredEvent = Event::where('is_featured', true)
            ->where('start_datetime', '>', now())
            ->inRandomOrder()
            ->first();
        return view('events.index', compact(
            'events',
            'categories',
            'todayCount',
            'freeCount',
            'featuredCount',
            'featuredEvent'
        ));
    }


    public function show(Event $event)
    {
        // Получаем похожие события (из той же категории или места)
        $relatedEvents = Event::where('event_id', '!=', $event->event_id)
            ->where(function ($query) use ($event) {
                if ($event->category) {
                    $query->where('category', $event->category);
                }
                if ($event->location) {
                    $query->orWhere('location', $event->location);
                }
            })
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->limit(4)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }


    public function venues()
    {
        // Извлекаем уникальные места проведения из событий
        $venues = Event::select('location')
            ->whereNotNull('location')->distinct()
            ->orderBy('location')->paginate(12);
        return view('venues.index', compact('venues'));
    }

    public function venueEvents($location)
    {
        // Декодируем URL-кодированное название локации
        $decodedLocation = urldecode($location);

        $events = Event::where('location', $decodedLocation)
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->paginate(10);
        return view('venues.show', [
            'location' => $decodedLocation,
            'events' => $events
        ]);
    }
    public function store(Event $event)
    {
        if (!Auth::check()) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Требуется авторизация'], 401)
                : redirect()->route('login');
        }
        if (Favorite::where('user_id', Auth::id())
            ->where('event_id', $event->event_id)->exists()
        ) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Событие уже в избранном'], 409)
                : back()->with('error', 'Событие уже в избранном');
        }
        Favorite::create([
            'user_id' => Auth::id(),
            'event_id' => $event->event_id
        ]);
        return request()->expectsJson()
            ? response()
            ->json(['success' => true, 'is_favorite' => true, 'message' => 'Событие добавлено в избранное'])
            : back()->with('success', 'Событие добавлено в избранное');
    }

    public function destroy(Event $event)
    {
        if (!Auth::check()) {
            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Требуется авторизация'], 401)
                : redirect()->route('login');
        }

        $deleted = Favorite::where('user_id', Auth::id())
            ->where('event_id', $event->event_id)
            ->delete();

        if ($deleted) {
            return request()->expectsJson()
                ? response()->json(['success' => true, 'is_favorite' => false, 'message'
                => 'Событие удалено из избранного'])
                : back()->with('success', 'Событие удалено из избранного');
        }

        return request()->expectsJson()
            ? response()->json(['success' => false, 'message' => 'Запись не найдена'], 404)
            : back()->with('error', 'Запись не найдена');
    }
}
