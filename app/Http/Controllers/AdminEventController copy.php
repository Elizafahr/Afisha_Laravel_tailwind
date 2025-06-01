<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;

class AdminEventwController extends Controller
{
    public function index()
    {
        $events = Event::with(['organizer'])->latest();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $organizers = Organizer::all();
        return view('admin.events.create', compact('organizers'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|max:100',
    //         'start_datetime' => 'required|date',
    //         'end_datetime' => 'required|date|after:start_datetime',
    //         'location' => 'required|max:255',
    //         'category' => 'required|max:50',
    //         'organizer_id' => 'required|exists:organizers,organizer_id',
    //         'description' => 'required',
    //         'booking_type' => 'required|in:seated,general',
    //         'age_restriction' => 'nullable|integer',
    //         'is_free' => 'required|boolean',
    //         'is_featured' => 'required|boolean',
    //         'price' => 'required|integer',
    //         'is_booking' => 'required|boolean',
    //         'link' => 'required|url',
    //         'poster' => 'nullable|image|max:2048'
    //     ]);

    //     $eventData = [
    //         'organizer_id' => $validated['organizer_id'],
    //         'title' => $validated['title'],
    //         'description' => $validated['description'],
    //         'category' => $validated['category'],
    //         'start_datetime' => $validated['start_datetime'],
    //         'end_datetime' => $validated['end_datetime'],
    //         'location' => $validated['location'],
    //         'age_restriction' => $validated['age_restriction'] ?? null,
    //         'is_published' => false,
    //         'is_free' => $validated['is_free'],
    //         'is_featured' => $validated['is_featured'],
    //         'price' => $validated['price'],
    //         'is_booking' => $validated['is_booking'],
    //         'link' => $validated['link'],
    //         'booking_type' => $validated['booking_type']
    //     ];

    //     if ($request->hasFile('poster')) {
    //         $path = $request->file('poster')->store('posters', 'public');
    //         $eventData['poster_url'] = $path;
    //     }

    //     $event = Event::create($eventData);
    //     // Если тип бронирования - по местам, создаем места
    //     if ($validated['booking_type'] === 'seated' && !empty($validated['seats'])) {
    //         $this->createSeatsForEvent($event, $validated['seats']);
    //     }

    //     return redirect()->route('admin.events.index')
    //         ->with('success', 'Мероприятие успешно создано');
    // }

//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'title' => 'required|string|max:100',
//         'description' => 'required|string',
//         'category' => 'required|string|max:50',
//         'start_datetime' => 'required|date',
//         'end_datetime' => 'required|date|after:start_datetime',
//         'location' => 'required|string|max:255',
//         'age_restriction' => 'nullable|integer|min:0|max:21',
//         'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         'is_free' => 'boolean',
//         'is_featured' => 'boolean',
//         'price' => 'required|integer|min:0',
//         'booking_type' => 'required|in:seated,general',
//         'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
//         'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
//         'link' => 'required|url|max:255',
//         'is_booking' => 'boolean',
//         'is_published' => 'boolean'
//     ]);

//     // Обработка загрузки постера
//     $posterPath = null;
//     if ($request->hasFile('poster_file')) {
//         $posterPath = $request->file('poster_file')->store('posters', 'public');
//     }

//     // Создание мероприятия
//     $event = Event::create([
//         'organizer_id' => auth()->user()->organizer->id,
//         'title' => $validated['title'],
//         'description' => $validated['description'],
//         'category' => $validated['category'],
//         'start_datetime' => $validated['start_datetime'],
//         'end_datetime' => $validated['end_datetime'],
//         'location' => $validated['location'],
//         'age_restriction' => $validated['age_restriction'] ?? null,
//         'poster_url' => $posterPath,
//         'is_free' => $validated['is_free'] ?? false,
//         'is_featured' => $validated['is_featured'] ?? false,
//         'price' => $validated['price'],
//         'booking_type' => $validated['booking_type'],
//         'link' => $validated['link'],
//         'is_booking' => $validated['is_booking'] ?? true,
//         'is_published' => $validated['is_published'] ?? false
//     ]);

//     // Если тип бронирования - по местам, создаем места
//     if ($validated['booking_type'] === 'seated') {
//         $this->createSeatsForEvent($event, $validated['rows'], $validated['columns']);
//     }

//     return redirect()->route('organizer.events.index')
//         ->with('success', 'Мероприятие успешно создано');
// }

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:100',
        'description' => 'required|string',
        'category' => 'required|string|max:50',
        'start_datetime' => 'required|date',
        'end_datetime' => 'required|date|after:start_datetime',
        'location' => 'required|string|max:255',
        'age_restriction' => 'nullable|integer|min:0|max:21',
        'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'required|integer|min:0',
        'booking_type' => 'required|in:seated,general',
        'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'link' => 'required|url|max:255',
        'is_booking' => 'boolean',
        'is_published' => 'boolean',
        'organizer_id' => 'required|exists:organizers,organizer_id'
    ]);

    // Handle file upload
    $posterPath = null;
    if ($request->hasFile('poster_file')) {
        $posterPath = $request->file('poster_file')->store('posters', 'public');
    }

    // Create event
    $event = Event::create([
        'organizer_id' => $validated['organizer_id'],
        'title' => $validated['title'],
        'description' => $validated['description'],
        'category' => $validated['category'],
        'start_datetime' => $validated['start_datetime'],
        'end_datetime' => $validated['end_datetime'],
        'location' => $validated['location'],
        'age_restriction' => $validated['age_restriction'] ?? null,
        'poster_url' => $posterPath,
        'is_free' => $validated['is_free'] ? 1 : 0,
        'is_featured' => $validated['is_featured'] ? 1 : 0,
        'price' => $validated['price'],
        'booking_type' => $validated['booking_type'],
        'link' => $validated['link'],
        'is_booking' => $validated['is_booking'] ? 1 : 0,
        'is_published' => $validated['is_published'] ? 1 : 0
    ]);

    // Create seats if needed
    if ($validated['booking_type'] === 'seated') {
        $this->createSeatsForEvent($event, $validated['rows'], $validated['columns']);
    }

    return redirect()->route('admin.events.index')
        ->with('success', 'Мероприятие успешно создано');
}

    // protected function createSeatsForEvent(Event $event, array $seatsData)
    // {
    //     foreach ($seatsData as $seatStr) {
    //         [$zone, $row, $number] = explode(',', $seatStr);

    //         $event->seats()->create([
    //             'zone' => $zone,
    //             'row' => $row,
    //             'number' => $number,
    //             'price' => 1000, // цена по умолчанию
    //             'is_reserved' => false
    //         ]);
    //     }
    // }
protected function createSeatsForEvent(Event $event, $rows, $columns)
{
    for ($row = 1; $row <= $rows; $row++) {
        for ($col = 1; $col <= $columns; $col++) {
            $event->seats()->create([
                'zone' => 'A',
                'row' => $row,
                'number' => $col,
                'price' => $event->price,
                'is_reserved' => false
            ]);
        }
    }
}
    public function edit(Event $event)
    {
        $organizers = Organizer::all();
        return view('admin.events.edit', compact('event', 'organizers'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'start_datetime' => 'required|date',
            'organizer_id' => 'required|exists:organizers,organizer_id',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'location' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $event->update($validated);

        return back()->with('success', 'Мероприятие обновлено');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Мероприятие удалено');
    }
}
