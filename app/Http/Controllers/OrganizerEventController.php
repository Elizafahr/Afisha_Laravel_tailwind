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
    public function index()
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
        // Получаем ID мероприятий организатора
        $eventIds = Event::where('organizer_id', $organizer->organizer_id)->pluck('event_id');
        // Получаем мероприятия с количеством бронирований
        $events = Event::whereIn('event_id', $eventIds)->withCount('bookings')->latest()->paginate(10);
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


        // Обработка загрузки файла постера
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
            $posterUrl = $fileName; // Сохраняем только имя файла
        }


        // Создаем мероприятие
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->category = $validated['category'];
        $event->start_datetime = $validated['start_datetime'];
        $event->end_datetime = $validated['end_datetime'];
        $event->location = $validated['location'];
        $event->age_restriction = $validated['age_restriction'] ?? null;
        $event->poster = $posterUrl; // Сохраняем либо URL, либо путь к файлу
        $event->is_free = $validated['is_free'] ?? 0; // По умолчанию 0 (платное)
        $event->price = $validated['price'] ?? 0;
        $event->is_published = $validated['is_published'] ?? 0; // По умолчанию не опубликовано
        $event->is_booking = $validated['is_booking'] ?? 1; // По умолчанию бронирование разрешено
        $event->organizer_id = $organizer->organizer_id;
        $event->booking_type = $validated['booking_type'];
        $event->link = $validated['link'] ?? null;
        $event->save();


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
    protected function generateSeats(Event $event, $rows, $columns)
    {
        $seats = [];
        $zones = ['Партер', 'Балкон', 'Ложа'];

        for ($row = 1; $row <= $rows; $row++) {
            $zone = $zones[0];
            if ($row > $rows * 0.7) {
                $zone = $zones[1];
            } elseif ($row <= $rows * 0.2) {
                $zone = $zones[2];
            }

            $priceMultiplier = 1.0;
            if ($zone === 'Ложа') $priceMultiplier = 2.0;
            elseif ($zone === 'Партер') $priceMultiplier = 1.5;

            for ($col = 1; $col <= $columns; $col++) {
                $seats[] = [
                    'event_id' => $event->event_id,
                    'seat_number' => chr(64 + $row) . $col,
                    'zone' => $zone,
                    'seat_row' => $row,
                    'is_reserved' => 0,
                    'price_multiplier' => $priceMultiplier,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
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
    $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
    if ($event->organizer_id !== $organizer->organizer_id) {
        abort(403, 'У вас нет прав для редактирования этого мероприятия');
    }

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
        'is_free' => 'nullable|in:0,1',
        'price' => 'nullable|numeric|min:0|required_if:is_free,0',
        'is_published' => 'nullable|in:0,1',
        'is_booking' => 'nullable|in:0,1',
        'booking_type' => 'required|in:seated,general',
        'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'link' => 'nullable|url',
        'remove_poster' => 'nullable|boolean'
    ]);

    // Сохраняем текущий постер по умолчанию
    $poster = $event->poster;

    // Обработка удаления постера (только если явно указано)
    if ($request->has('remove_poster') && $request->remove_poster) {
        if ($poster && !filter_var($poster, FILTER_VALIDATE_URL)) {
            $oldFilePath = public_path('images/'.$poster);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        $poster = null;
    }
    // Обработка загрузки нового файла
    elseif ($request->hasFile('poster_file')) {
        // Удаляем старый файл, если он был (и это не URL)
        if ($poster && !filter_var($poster, FILTER_VALIDATE_URL)) {
            $oldFilePath = public_path('images/'.$poster);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Сохраняем новый файл
        $file = $request->file('poster_file');
        $fileName = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('images'), $fileName);
        $poster = $fileName;
    }
    // Обработка URL постера (только если явно указан новый URL)
    elseif ($request->filled('poster_url') && $request->poster_url !== $event->poster) {
        // Удаляем старый файл, если он был (и это не URL)
        if ($poster && !filter_var($poster, FILTER_VALIDATE_URL)) {
            $oldFilePath = public_path('images/'.$poster);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        $poster = $validated['poster_url'];
    }

    // Обновляем данные мероприятия
    $event->update([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'category' => $validated['category'],
        'start_datetime' => $validated['start_datetime'],
        'end_datetime' => $validated['end_datetime'],
        'location' => $validated['location'],
        'age_restriction' => $validated['age_restriction'] ?? null,
        'poster' => $poster, // Сохраняем новое значение или оставляем старое
        'is_free' => $validated['is_free'] ?? 0,
        'price' => $validated['is_free'] ? 0 : ($validated['price'] ?? 0),
        'is_published' => $validated['is_published'] ?? 0,
        'is_booking' => $validated['is_booking'] ?? 1,
        'booking_type' => $validated['booking_type'],
        'link' => $validated['link'] ?? null,
    ]);

    // Обработка мест для сидячих мероприятий
    if ($validated['booking_type'] === 'seated' &&
        ($event->wasChanged('booking_type') || $event->wasChanged('rows') || $event->wasChanged('columns'))) {

        DB::table('Seats')->where('event_id', $event->event_id)->delete();
        $this->generateSeats($event, $validated['rows'], $validated['columns']);
    }

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
    }

    public function seats(Event $event)
    {
        $this->authorize('manage', $event);
        return view('organizer.events.seats', compact('event'));
    }

    public function storeSeats(Request $request, Event $event)
    {
        $this->authorize('manage', $event);
    }
}
