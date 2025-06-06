<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Organizer;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    $bookings = Booking::whereHas('event', function ($query) use ($organizer) {
            $query->where('organizer_id', $organizer->organizer_id);
        })
        ->with(['ticket.event', 'seat.event', 'user', 'event'])
        ->whereIn('status', ['pending', 'confirmed']) // Only active bookings
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('organizer.events.bookings', compact('bookings'));
}
public function cancelBooking(Booking $booking)
{
    // Verify the booking belongs to organizer's events
    $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();

    $isOrganizerBooking = $booking->event && $booking->event->organizer_id == $organizer->id;


    DB::beginTransaction();
    try {
        // Update booking status
        $booking->update(['status' => 'cancelled']);

        // Release seats if any
        if ($booking->seat_id) {
            Seat::where('seat_id', $booking->seat_id)
                ->update(['is_reserved' => 0]);
        }

        // Return tickets if any
        if ($booking->ticket_id) {
            Ticket::where('ticket_id', $booking->ticket_id)
                ->increment('quantity_available', $booking->quantity);
        }

        DB::commit();
        return back()->with('success', 'Booking cancelled successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
    }
}
}
