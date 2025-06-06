<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function showBookingForm(Event $event)
    {
        // Проверка доступности бронирования
        if (!$event->is_booking || $event->is_free) {
            if (!empty($event->link)) {
                return redirect($event->link);
            }
            return redirect()->route('events.show', $event);
        }
        // Получение доступных билетов
        $tickets = Ticket::where('event_id', $event->event_id)
            ->where('booking_start', '<=', now())
            ->where('booking_end', '>=', now())
            ->where('quantity_available', '>', 0)
            ->get();
        // Получение доступных мест
        $seats = Seat::where('event_id', $event->event_id)
            ->where('is_reserved', 0)->get();
        return view('bookings.create', compact('event', 'tickets', 'seats'));
    }

    public function book(Request $request, Event $event)
    {
        // Проверка доступности бронирования
        if (!$event->is_booking || $event->is_free) {
            if (!empty($event->link)) {
                return redirect($event->link);
            }
            return back()->with('error', 'Бронирование для этого мероприятия недоступно');
        }

        // Валидация входных данных
        $validated = $request->validate([
            'ticket_id' => 'nullable|integer|exists:tickets,ticket_id',
            'seat_id' => 'nullable|integer|exists:seats,seat_id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Проверка доступности билетов
        if ($request->filled('ticket_id')) {
            $ticket = Ticket::find($request->ticket_id);
            if (!$ticket || $ticket->quantity_available < $request->quantity) {
                return back()->with('error', 'Выбранного типа билетов недостаточно');
            }
        }

        // Проверка доступности мест
        if ($request->filled('seat_id')) {
            $seat = Seat::find($request->seat_id);
            if (!$seat || $seat->is_reserved) {
                return back()->with('error', 'Выбранное место уже занято');
            }
        }

        // Расчет общей стоимости
        $price = 0;
        if ($request->filled('ticket_id')) {
            $price = $ticket->price * $request->quantity;
        } elseif ($request->filled('seat_id')) {
            $price = $event->price * $seat->price_multiplier * $request->quantity;
        } else {
            $price = $event->price * $request->quantity;
        }

        try {
            // Создание бронирования
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'ticket_id' => $request->ticket_id,
                'seat_id' => $request->seat_id,
                'quantity' => $request->quantity,
                'total_price' => $price,
                'status' => 'confirmed',
                'payment_method' => 'online_payment' // Можно сделать выбор пользователем
            ]);
            // Обновление доступности билетов
            if ($request->filled('ticket_id')) {
                $ticket->decrement('quantity_available', $request->quantity);
            }
            // Обновление статуса мест
            if ($request->filled('seat_id')) {
                $seat->update(['is_reserved' => 1]);
            }
            // Генерация номеров билетов
            $ticketNumbers = [];
            for ($i = 0; $i < $request->quantity; $i++) {
                $ticketNumbers[] = 'TICKET-' . Str::upper(Str::random(8));
            }
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Билеты успешно забронированы!')
                ->with('ticketNumbers', $ticketNumbers);
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при бронировании: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        // Проверка прав доступа к бронированию
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        return view('bookings.show', compact('booking'));
    }

    public function create(Event $event)
    {
        // Проверка возможности бронирования
        if (!$event->is_booking || $event->is_free) {
            return redirect()->back()->with('error', 'Бронирование для этого мероприятия недоступно');
        }
        // Получение доступных билетов и мест
        $tickets = $event->tickets()->where('quantity_available', '>', 0)->get();
        $seats = $event->seats()->where('is_reserved', false)->get();
        return view('bookings.create', compact('event', 'tickets', 'seats'));
    }

    // public function store(Request $request, Event $event)
    // {
    //     // Проверка авторизации пользователя
    //     if (!auth()->check()) {
    //         return redirect()->route('login')->with('error', 'Для бронирования необходимо войти в систему');
    //     }
    //     // Валидация входных данных
    //     $validated = $request->validate([
    //         'ticket_id' => 'nullable|integer|exists:tickets,ticket_id',
    //         'selected_seats' => 'required|json',
    //     ]);
    //     // Разбор выбранных мест
    //     $selectedSeats = json_decode($request->selected_seats, true);
    //     if (empty($selectedSeats)) {
    //         return back()->with('error', 'Не выбрано ни одного места');
    //     }
    //     // Проверка доступности билетов
    //     $ticket = null;
    //     if ($request->filled('ticket_id')) {
    //         $ticket = Ticket::find($request->ticket_id);
    //         if (!$ticket || $ticket->quantity_available < count($selectedSeats)) {
    //             return back()->with('error', 'Недостаточно доступных билетов выбранного типа');
    //         }
    //     }
    //     // Получение информации о местах из БД
    //     $seatIds = array_column($selectedSeats, 'id');
    //     $seats = Seat::whereIn('seat_id', $seatIds)
    //         ->where('event_id', $event->event_id)
    //         ->where('is_reserved', false)
    //         ->get()
    //         ->keyBy('seat_id');
    //     // Проверка доступности всех мест
    //     foreach ($selectedSeats as $seat) {
    //         if (!isset($seats[$seat['id']])) {
    //             return back()->with('error', 'Место ' . $seat['id'] . ' не найдено или уже занято');
    //         }
    //     }
    //     // Расчет общей стоимости
    //     $totalPrice = $ticket ? ($ticket->price * count($selectedSeats)) :
    //         array_sum(array_column($selectedSeats, 'price'));
    //     DB::beginTransaction();
    //     try {
    //         // Создание бронирования
    //         $booking = Booking::create([
    //             'user_id' => auth()->id(),
    //             'event_id' => $event->event_id,
    //             'ticket_id' => $request->ticket_id,
    //             'quantity' => count($selectedSeats),
    //             'total_price' => $totalPrice,
    //             'status' => 'confirmed',
    //             'payment_method' => 'online',
    //         ]);

    //         // Резервирование мест
    //         Seat::whereIn('seat_id', $seatIds)->update([
    //             'is_reserved' => true,
    //             'booking_id' => $booking->booking_id
    //         ]);
    //         // Обновление доступности билетов
    //         if ($ticket) {
    //             $ticket->decrement('quantity_available', count($selectedSeats));
    //         }
    //         DB::commit();
    //         return redirect()->route('bookings.show', $booking)
    //             ->with('success', 'Бронирование успешно завершено!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Ошибка при бронировании: ' . $e->getMessage());
    //     }
    // }


public function cancel(Booking $booking)
{
    // Проверка прав пользователя
    if ($booking->user_id !== auth()->id()) {
        abort(403);
    }

    // Проверка возможности отмены
    if (!in_array($booking->status, ['pending', 'confirmed'])) {
        return back()->with('error', 'Это бронирование нельзя отменить');
    }

    DB::beginTransaction();
    try {
        // Освобождаем места
        if ($booking->seats->isNotEmpty()) {
            Seat::where('booking_id', $booking->booking_id)
                ->update(['is_reserved' => false, 'booking_id' => null]);
        }

        // Возвращаем билеты
        if ($booking->ticket) {
            $booking->ticket->increment('quantity_available', $booking->quantity);
        }

        // Обновляем статус бронирования
        $booking->update(['status' => 'cancelled']);

        DB::commit();
        return back()->with('success', 'Бронирование успешно отменено');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Произошла ошибка при отмене бронирования');
    }
}
    public function store(Request $request, Event $event)
    {
        // Валидация в зависимости от типа мероприятия
        $rules = [
            'payment_method' => 'required|in:cash,card'
        ];

        if ($event->booking_type === 'seated') {
            $rules['selected_seats'] = 'required|json';
        } else {
            $rules['ticket_id'] = 'nullable|exists:tickets,ticket_id';
            $rules['quantity'] = 'required|integer|min:1';
        }

        $validated = $request->validate($rules);

        // Для мероприятий с рассадкой
        if ($event->booking_type === 'seated') {
            $selectedSeats = json_decode($request->selected_seats, true);

            // Проверка доступности мест
            $seats = Seat::whereIn('seat_id', array_column($selectedSeats, 'id'))
                ->where('event_id', $event->event_id)
                ->where('is_reserved', false)
                ->get();

            if ($seats->count() !== count($selectedSeats)) {
                throw new \Exception('Некоторые места уже заняты');
            }

            // Создание бронирования
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'event_id' => $event->event_id,
                'quantity' => count($selectedSeats),
                'total_price' => array_sum(array_column($selectedSeats, 'price')),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // Резервирование мест
            Seat::whereIn('seat_id', $seats->pluck('seat_id'))
                ->update([
                    'is_reserved' => true,
                    'booking_id' => $booking->booking_id
                ]);
        }
        // Для мероприятий без рассадки
        else {
            $ticket = null;
            $price = $event->price;

            if ($request->ticket_id) {
                $ticket = Ticket::findOrFail($request->ticket_id);
                $price = $ticket->price;

                if ($ticket->quantity_available < $request->quantity) {
                    throw new \Exception('Недостаточно доступных билетов');
                }
            }

            $booking = Booking::create([
                'user_id' => auth()->id(),
                'event_id' => $event->event_id,
                'ticket_id' => $request->ticket_id,
                'quantity' => $request->quantity,
                'total_price' => $price * $request->quantity,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            if ($ticket) {
                $ticket->decrement('quantity_available', $request->quantity);
            }
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Бронирование успешно завершено!');
    }
    public function bookSeats(Request $request, Event $event)
    {
        if (!auth()->check()) {        // Проверка авторизации
            return redirect()->route('login')->with('error', 'Для бронирования необходимо войти в систему');
        }
        // Валидация входных данных
        $validated = $request->validate([
            'ticket_id' => 'nullable|integer|exists:tickets,ticket_id',
            'selected_seats' => 'required|json',
        ]);
        $selectedSeats = json_decode($request->selected_seats, true);        // Разбор выбранных мест
        if (empty($selectedSeats)) {
            return back()->with('error', 'Не выбрано ни одного места');
        }
        // Проверка доступности билетов
        $ticket = null;
        if ($request->filled('ticket_id')) {
            $ticket = Ticket::find($request->ticket_id);
            if (!$ticket || $ticket->quantity_available < count($selectedSeats)) {
                return back()->with('error', 'Недостаточно доступных билетов выбранного типа');
            }
        }
        $seatIds = array_column($selectedSeats, 'id');        // Получение информации о местах из БД
        $seats = Seat::whereIn('seat_id', $seatIds)
            ->where('event_id', $event->event_id)
            ->where('is_reserved', false)
            ->get()
            ->keyBy('seat_id');

        foreach ($selectedSeats as $seat) {        // Проверка доступности всех мест
            if (!isset($seats[$seat['id']])) {
                return back()->with('error', 'Место ' . $seat['id'] . ' не найдено или уже занято');
            }
        }

        $totalPrice = $ticket ? ($ticket->price * count($selectedSeats)) :        // Расчет общей стоимости
            array_sum(array_column($selectedSeats, 'price'));

        DB::beginTransaction();
        try {
            // Создание бронирования
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'event_id' => $event->event_id,
                'ticket_id' => $request->ticket_id,
                'quantity' => count($selectedSeats),
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'payment_method' => 'online',
            ]);

            // Резервирование мест
            Seat::whereIn('seat_id', $seatIds)->update([
                'is_reserved' => true,
                'booking_id' => $booking->booking_id
            ]);

            // Обновление доступности билетов
            if ($ticket) {
                $ticket->decrement('quantity_available', count($selectedSeats));
            }

            DB::commit();
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Бронирование успешно завершено!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при бронировании: ' . $e->getMessage());
        }
    }
}
