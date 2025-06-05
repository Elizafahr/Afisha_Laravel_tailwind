<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Organizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('bookings')->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }
    public function create()
    {
          $organizers = Organizer::all(); // Или любой другой запрос
        return view('organizer.events.create', [
            'title' => 'Создание мероприятия',
            'organizers' =>  $organizers
        ]);}
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
            'poster' => 'nullable|url',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_free' => 'nullable|in:0,1', // Принимает 0 или 1
            'price' => 'nullable|numeric|min:0|required_if:is_free,0',
            'is_published' => 'nullable|in:0,1', // Принимает 0 или 1
            'is_booking' => 'nullable|in:0,1', // Принимает 0 или 1
            'booking_type' => 'required|in:seated,general',
            'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
            'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
            'link' => 'nullable|url',
            'organizer_id' => 'required|exists:organizers,organizer_id',

        ]);
        $posterUrl = $validated['poster'] ?? null;
        // Создаем мероприятие
        $event = new Event();
        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->category = $validated['category'];
        $event->start_datetime = $validated['start_datetime'];
        $event->end_datetime = $validated['end_datetime'];
        $event->location = $validated['location'];
        $event->age_restriction = $validated['age_restriction'] ?? null;
        $event->poster = $posterUrl;
        $event->is_free = $validated['is_free'] ?? 0; // По умолчанию 0 (платное)
        $event->price = $validated['price'] ?? 0;
        $event->is_published = $validated['is_published'] ?? 0; // По умолчанию не опубликовано
        $event->is_booking = $validated['is_booking'] ?? 1; // По умолчанию бронирование разрешено
        // $event->organizer_id = $validated['organizer_id'];
        $event->organizer_id = 3;
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
        return redirect()->route('admin.dashboard')
            ->with('success', 'Мероприятие успешно создано!');
    }
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
        ]);
        $event->update($validated);
        // Обновление изображения
        if ($request->hasFile('image')) {
            $event->clearMediaCollection('events');
            $event->addMediaFromRequest('image')
                ->toMediaCollection('events');
        }
        return back()->with('success', 'Мероприятие обновлено');
    }
    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Мероприятие удалено');
    }
}
