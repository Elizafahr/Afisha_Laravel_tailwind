<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\Booking;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    public function index()
    {
        $verifiedOrganizers = Organizer::withCount('events')
            ->where('is_verified', true)
            ->orderByDesc('events_count')->limit(6)->get();
        $allOrganizers = Organizer::withCount('events')
            ->orderByDesc('is_verified')->orderByDesc('events_count')->paginate(10);
        return view('organizers.index', compact('verifiedOrganizers', 'allOrganizers'));
    }

    public function show(Organizer $organizer)
    {
        $events = $organizer->events()
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->paginate(6);
        return view('organizers.show', compact('organizer', 'events'));
    }
    public function apply(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'terms' => 'required|accepted',
        ]);
        return back()->with('success', 'Ваша заявка успешно отправлена! Мы рассмотрим ее в ближайшее время.');
    }
    public function dashboard()
    {
        // Получаем текущего организатора
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
        // Получаем ID мероприятий организатора
        $eventIds = Event::where('organizer_id', $organizer->organizer_id)
            ->pluck('event_id');
        // Получаем мероприятия с количеством бронирований
        $events = Event::whereIn('event_id', $eventIds)
            ->withCount('bookings')
            ->latest()
            ->paginate(10);
        // Общее количество бронирований
        $totalBookings = Booking::whereHas('ticket', function ($query) use ($eventIds) {
            $query->whereIn('event_id', $eventIds);
        })->orWhereHas('seat', function ($query) use ($eventIds) {
                $query->whereIn('event_id', $eventIds);
            })->count();
        return view('organizer.dashboard', compact('events', 'totalBookings', 'organizer'));
    }
    public function bookings()
    {
        $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

        $bookings = Booking::whereHas('ticket.event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->id);
        })
            ->orWhereHas('seat.event', function ($query) use ($organizer) {
                $query->where('organizer_id', $organizer->id);
            })
            ->with(['ticket.event', 'seat.event', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('organizer.events.bookings', compact('bookings'));
    }
}
