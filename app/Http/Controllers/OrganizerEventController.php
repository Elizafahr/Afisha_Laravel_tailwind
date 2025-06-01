<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizerEventController extends Controller
{
    // public function index()
    // {
    //     $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

    //     // Получаем ID мероприятий организатора
    //     $eventIds = Event::where('organizer_id', $organizer->organizer_id)
    //         ->pluck('event_id');

    //     // Получаем мероприятия с количеством бронирований
    //     $events = Event::whereIn('event_id', $eventIds)
    //         ->withCount('bookings')
    //         ->latest()
    //         ->paginate(10);

    //     return view('organizer.events.index', compact('events'));
    // }

    public function index()
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

        // Получаем ID мероприятий организатора
        $eventIds = Event::where('organizer_id', $organizer->organizer_id)
            ->pluck('event_id');

        // Получаем мероприятия с количеством бронирований
        $events = Event::whereIn('event_id', $eventIds)
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
         $organizers = Organizer::all(); // Или любой другой запрос
        return view('organizer.events.create', [
            'title' => 'Создание мероприятия',
            'organizers' =>  $organizers
        ]);

    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'start_datetime' => 'required|date',
    //         'end_datetime' => 'required|date|after:start_datetime',
    //         'location' => 'required|string',
    //         'category_id' => 'required|exists:categories,id',
    //         'image' => 'nullable|image|max:2048'
    //     ]);

    //     $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

    //     $event = new Event($validated);
    //     $event->organizer_id = $organizer->id;

    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('events', 'public');
    //         $event->image_url = $path;
    //     }

    //     $event->save();

    //     return redirect()->route('organizer.events.index')
    //         ->with('success', 'Мероприятие успешно создано!');
    // }


    // public function store(Request $request)
    // {
    //     // Validate the request data
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //         'category' => 'required|in:concert,festival,exhibition,theater',
    //         'start_datetime' => 'required|date',
    //         'end_datetime' => 'required|date|after:start_datetime',
    //         'location' => 'required|string',
    //         'age_restriction' => 'nullable|integer|min:0',
    //         'poster_url' => 'nullable|url',
    //         'is_free' => 'boolean',
    //         'price' => 'nullable|numeric|min:0|required_if:is_free,false',
    //         'is_published' => 'boolean',
    //         'booking_type' => 'required|in:seated,general',
    //         'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
    //         'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
    //     ]);

    //     // Get the organizer associated with the current user
    //     $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

    //     // Create the event
    //     $event = new Event();
    //     $event->title = $validated['title'];
    //     $event->description = $validated['description'];
    //     $event->category = $validated['category'];
    //     $event->start_datetime = $validated['start_datetime'];
    //     $event->end_datetime = $validated['end_datetime'];
    //     $event->location = $validated['location'];
    //     $event->age_restriction = $validated['age_restriction'] ?? null;
    //     $event->poster_url = $validated['poster_url'] ?? null;
    //     $event->is_free = $validated['is_free'] ?? false;
    //     $event->price = $validated['is_free'] ? 0 : ($validated['price'] ?? 0);
    //     $event->is_published = $validated['is_published'] ?? false;
    //     $event->organizer_id = $organizer->id;
    //     $event->booking_type = $validated['booking_type'];
    //     $event->save();


    //     // Save the event
    //     $event->save();
    //     if ($request->booking_type === 'seated') {
    //         $this->generateSeats($event, $request->rows, $request->columns);
    //     }
    //     // Redirect with success message
    //     return redirect()->route('organizer.events.index')
    //         ->with('success', 'Мероприятие успешно создано!');
    // }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:concert,festival,exhibition,theater',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required|string',
            'age_restriction' => 'nullable|integer|min:0',
            'poster_url' => 'nullable|url',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_free' => 'nullable|in:0,1', // Принимает 0 или 1
        'price' => 'nullable|numeric|min:0|required_if:is_free,0',
            'is_published' => 'nullable|in:0,1', // Принимает 0 или 1
            'is_booking' => 'nullable|in:0,1', // Принимает 0 или 1
            'booking_type' => 'required|in:seated,general',
            'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
            'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
            'link' => 'nullable|url'
        ]);


        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();


        $posterUrl = $validated['poster_url'] ?? null;


        // Создаем мероприятие
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->category = $validated['category'];
        $event->start_datetime = $validated['start_datetime'];
        $event->end_datetime = $validated['end_datetime'];
        $event->location = $validated['location'];
        $event->age_restriction = $validated['age_restriction'] ?? null;
        $event->poster_url = $posterUrl;
        $event->is_free = $validated['is_free'] ?? 0; // По умолчанию 0 (платное)
        $event->price = $validated['price'] ?? 0;
        $event->is_published = $validated['is_published'] ?? 0; // По умолчанию не опубликовано
        $event->is_booking = $validated['is_booking'] ?? 1; // По умолчанию бронирование разрешено
        $event->organizer_id = $organizer->organizer_id;
        $event->booking_type = $validated['booking_type'];
        $event->link = $validated['link'] ?? null;
        $event->save();

        if ($request->hasFile('poster_file')) {
            $path = $request->file('poster_file')->store('event_posters', 'public');
            $posterUrl = Storage::url($path);
        }
        if ($request->booking_type === 'seated') {
            $this->generateSeats($event, $request->rows, $request->columns);
        }

        return redirect()->route('organizer.events.index')
            ->with('success', 'Мероприятие успешно создано!');
    }
    public function show(Event $event)
    {
        $this->authorize('view', $event);
        return view('organizer.events.show', compact('event'));
    }


    //   public function edit(Event $event)
    //     {
    //         $this->authorize('update', $event);

    //         return view('organizer.events.edit', [
    //             'event' => $event,
    //             'title' => 'Редактирование мероприятия'
    //         ]);
    //     }


    protected function generateSeats(Event $event, $rows, $columns)
    {
        $seats = [];
        $zones = ['Партер', 'Балкон', 'Ложа']; // Example zones

        for ($row = 1; $row <= $rows; $row++) {
            // Determine zone based on row number
            $zone = $zones[0]; // Default to first zone
            if ($row > $rows * 0.7) {
                $zone = $zones[1]; // Balcony for higher rows
            } elseif ($row <= $rows * 0.2) {
                $zone = $zones[2]; // Lodge for first 20% rows
            }

            // Determine price multiplier based on zone
            $priceMultiplier = 1.0;
            if ($zone === 'Ложа') $priceMultiplier = 2.0;
            elseif ($zone === 'Партер') $priceMultiplier = 1.5;

            for ($col = 1; $col <= $columns; $col++) {
                $seats[] = [
                    'event_id' => $event->event_id,
                    'seat_number' => chr(64 + $row) . $col, // A1, A2, etc.
                    'zone' => $zone,
                    'seat_row' => $row,
                    'is_reserved' => 0,
                    'price_multiplier' => $priceMultiplier,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }    // Bulk insert for better performance
        DB::table('Seats')->insert($seats);
    }
   public function edit(Event $event)
{
        $organizers = Organizer::all();

    // Проверка прав доступа
    if (auth()->user()->id !== $event->user_id) {
        abort(403, 'У вас нет прав для редактирования этого мероприятия');
    }

    return view('organizer.events.edit', compact('event', 'organizers'));
}
public function update(Request $request, Event $event)
{
    // Проверка прав доступа
    if (auth()->user()->id !== $event->user_id) {
        abort(403, 'У вас нет прав для редактирования этого мероприятия');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date|after:start_datetime',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_published' => 'boolean',
        'remove_image' => 'boolean',
    ]);

    // Обработка изображения
    if ($request->hasFile('image')) {
        // Удаляем старое изображение, если есть
        if ($event->image && Storage::exists('public/' . $event->image)) {
            Storage::delete('public/' . $event->image);
        }

        // Сохраняем новое изображение
        $imagePath = $request->file('image')->store('events', 'public');
        $validated['image'] = $imagePath;
    } elseif ($request->has('remove_image') && $event->image) {
        Storage::delete('public/' . $event->image);
        $validated['image'] = null;
    }

    $event->update($validated);

    return redirect()->route('organizer.events.index')
                   ->with('success', 'Мероприятие успешно обновлено');
}
    public function destroy(Event $event)
{
    // Проверка прав (если организатор может удалять только свои события)
    if (auth()->user()->id !== $event->user_id) {
        abort(403, 'У вас нет прав для удаления этого мероприятия');
    }

    // Удаление изображения (если используется)
    if ($event->image) {
        Storage::delete('public/' . $event->image);
    }

    $event->delete();

    return redirect()->route('organizer.events.index')
        ->with('success', 'Мероприятие успешно удалено');
}
    public function tickets(Event $event)
    {
        $this->authorize('manage', $event);
        return view('organizer.events.tickets', compact('event'));
    }

    public function storeTickets(Request $request, Event $event)
    {
        $this->authorize('manage', $event);
        // Логика сохранения билетов
    }

    public function seats(Event $event)
    {
        $this->authorize('manage', $event);
        return view('organizer.events.seats', compact('event'));
    }

    public function storeSeats(Request $request, Event $event)
    {
        $this->authorize('manage', $event);
        // Логика сохранения мест
    }
}
