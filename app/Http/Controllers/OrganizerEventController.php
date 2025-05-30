<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Seat;
use Illuminate\Http\Request;

class OrganizerEventController extends Controller
{
    // Панель организатора
    public function dashboard()
{
    $organizer = auth()->user()->organizer;

    if (!$organizer) {
         return redirect()->route('home')->with('error', 'You are not registered as an organizer');
    }

    $events = Event::where('organizer_id', $organizer->organizer_id)
                  ->withCount('bookings')
                  ->latest()
                  ->paginate(10);

    $totalBookings = $organizer->events()->withCount('bookings')->get()->sum('bookings_count');

    return view('organizer.dashboard', compact('events', 'organizer', 'totalBookings'));
}

    // Список мероприятий организатора
    public function index()
    {
        $events = auth()->user()->organizer->events()->latest()->paginate(10);
        return view('organizer.events.index', compact('events'));
    }

    // Форма создания мероприятия
    public function create()
    {
        return view('organizer.events.create');
    }

    // Сохранение мероприятия
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required|string',
            'age_restriction' => 'nullable|integer',
            'poster_url' => 'nullable|url',
            'is_free' => 'boolean',
            'price' => 'required_if:is_free,false|numeric|min:0',
        ]);

        $organizer = auth()->user()->organizer;

        $event = $organizer->events()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'],
            'location' => $validated['location'],
            'age_restriction' => $validated['age_restriction'] ?? null,
            'poster_url' => $validated['poster_url'] ?? null,
            'is_free' => $request->has('is_free'),
            'price' => $validated['price'] ?? 0,
            'is_published' => false,
        ]);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Мероприятие создано!');
    }

    // Просмотр мероприятия
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load(['tickets', 'seats', 'bookings', 'reviews']);
        return view('organizer.events.show', compact('event'));
    }

    // Форма редактирования
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('organizer.events.edit', compact('event'));
    }

    // Обновление мероприятия
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required|string',
            'age_restriction' => 'nullable|integer',
            'poster_url' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $event->update($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Мероприятие обновлено!');
    }

    // Управление билетами
    public function tickets(Event $event)
    {
        $this->authorize('update', $event);
        $tickets = $event->tickets;
        return view('organizer.events.tickets', compact('event', 'tickets'));
    }

    // Сохранение билетов
    public function storeTickets(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'tickets' => 'required|array',
            'tickets.*.ticket_type' => 'required|string',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.quantity_available' => 'required|integer|min:1',
            'tickets.*.booking_start' => 'required|date',
            'tickets.*.booking_end' => 'required|date|after:tickets.*.booking_start',
        ]);

        // Удаляем старые билеты
        $event->tickets()->delete();

        // Создаем новые
        foreach ($validated['tickets'] as $ticketData) {
            $event->tickets()->create($ticketData);
        }

        return redirect()->route('organizer.events.tickets', $event)->with('success', 'Билеты обновлены!');
    }

    // Управление местами
    public function seats(Event $event)
    {
        $this->authorize('update', $event);
        $seats = $event->seats;
        return view('organizer.events.seats', compact('event', 'seats'));
    }

    // Сохранение мест
    public function storeSeats(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'seats' => 'required|array',
            'seats.*.seat_number' => 'required|string',
            'seats.*.zone' => 'required|string',
            'seats.*.seat_row' => 'required|integer',
            'seats.*.price_multiplier' => 'required|numeric|min:0.1',
        ]);

        // Удаляем старые места
        $event->seats()->delete();

        // Создаем новые
        foreach ($validated['seats'] as $seatData) {
            $event->seats()->create($seatData);
        }

        return redirect()->route('organizer.events.seats', $event)->with('success', 'Места обновлены!');
    }
}
