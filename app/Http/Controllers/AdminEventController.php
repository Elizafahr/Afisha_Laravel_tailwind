<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('bookings')->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'location' => 'required',
            'price' => 'required|numeric|min:0',
            'is_booking' => 'boolean',
            'is_free' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $event = Event::create($validated);

        if ($request->hasFile('image')) {
            $event->addMediaFromRequest('image')
                ->toMediaCollection('events');
        }

        return redirect()->route('admin.events.index')->with('success', 'Мероприятие создано');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            // те же правила, что и для store
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
