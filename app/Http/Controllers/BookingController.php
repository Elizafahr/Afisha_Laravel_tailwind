<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function showBookingForm(Event $event)
    {
        // Проверяем, доступно ли бронирование
        if (!$event->is_booking || $event->is_free) {
            // Если есть внешняя ссылка - перенаправляем
            if (!empty($event->link)) {
                return redirect($event->link);
            }
            // Иначе на страницу события
            return redirect()->route('events.show', $event);
        }

        // Получаем доступные билеты и места
        $tickets = Ticket::where('event_id', $event->event_id)
            ->where('booking_start', '<=', now())
            ->where('booking_end', '>=', now())
            ->where('quantity_available', '>', 0)
            ->get();

        $seats = Seat::where('event_id', $event->event_id)
            ->where('is_reserved', 0)
            ->get();

        return view('bookings.create', compact('event', 'tickets', 'seats'));
    }

    public function book(Request $request, Event $event)
    {
        // Проверяем, доступно ли бронирование
        if (!$event->is_booking || $event->is_free) {
            if (!empty($event->link)) {
                return redirect($event->link);
            }
            return back()->with('error', 'Бронирование для этого мероприятия недоступно');
        }

        // Валидация данных
        $validated = $request->validate([
            'ticket_id' => 'nullable|integer|exists:tickets,ticket_id',
            'seat_id' => 'nullable|integer|exists:seats,seat_id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Проверяем доступность билета
        if ($request->filled('ticket_id')) {
            $ticket = Ticket::find($request->ticket_id);
            if (!$ticket || $ticket->quantity_available < $request->quantity) {
                return back()->with('error', 'Выбранного типа билетов недостаточно');
            }
        }

        // Проверяем доступность места
        if ($request->filled('seat_id')) {
            $seat = Seat::find($request->seat_id);
            if (!$seat || $seat->is_reserved) {
                return back()->with('error', 'Выбранное место уже занято');
            }
        }

        // Рассчитываем цену
        $price = 0;
        if ($request->filled('ticket_id')) {
            $price = $ticket->price * $request->quantity;
        } elseif ($request->filled('seat_id')) {
            $price = $event->price * $seat->price_multiplier * $request->quantity;
        } else {
            $price = $event->price * $request->quantity;
        }

        try {
            // Создаем бронирование
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'ticket_id' => $request->ticket_id,
                'seat_id' => $request->seat_id,
                'quantity' => $request->quantity,
                'total_price' => $price,
                'status' => 'confirmed',
                'payment_method' => 'online_payment' // Можно изменить на выбор пользователя
            ]);

            // Обновляем доступность билетов/мест
            if ($request->filled('ticket_id')) {
                $ticket->decrement('quantity_available', $request->quantity);
            }

            if ($request->filled('seat_id')) {
                $seat->update(['is_reserved' => 1]);
            }

            // Генерируем номера билетов
            $ticketNumbers = [];
            for ($i = 0; $i < $request->quantity; $i++) {
                $ticketNumbers[] = 'TICKET-' . Str::upper(Str::random(8));
            }

            // Можно сохранить номера билетов или отправить их пользователю

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Билеты успешно забронированы!')
                ->with('ticketNumbers', $ticketNumbers);
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при бронировании: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        // Проверяем, что пользователь просматривает свое бронирование
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function create(Event $event)
    {
        // Проверка доступности бронирования
        if (!$event->is_booking || $event->is_free) {
            return redirect()->back()->with('error', 'Бронирование для этого мероприятия недоступно');
        }

        // Получаем доступные билеты и места
        $tickets = $event->tickets()->where('quantity_available', '>', 0)->get();
        $seats = $event->seats()->where('is_reserved', false)->get();

        return view('bookings.create', compact('event', 'tickets', 'seats'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_id' => 'nullable|exists:tickets,ticket_id',
            'seat_id' => 'nullable|exists:seats,seat_id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Создаем бронирование
            $booking = $event->bookings()->create([
                'user_id' => auth()->id(),
                'ticket_id' => $validated['ticket_id'],
                'seat_id' => $validated['seat_id'],
                'quantity' => $validated['quantity'],
                'total_price' => $this->calculateTotalPrice($event, $validated),
                'status' => 'confirmed',
                'booking_code' => Str::upper(Str::random(8)),
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Бронирование успешно завершено!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка бронирования: ' . $e->getMessage());
        }
    }

    private function calculateTotalPrice(Event $event, array $data)
    {
        // Логика расчета цены
        $price = $event->price;

        if ($data['ticket_id']) {
            $ticket = Ticket::find($data['ticket_id']);
            $price = $ticket->price;
        } elseif ($data['seat_id']) {
            $seat = Seat::find($data['seat_id']);
            $price *= $seat->price_multiplier;
        }

        return $price * $data['quantity'];
    }
}
