<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    // Главная панель
    public function dashboard()
    {
        $stats = [
            'events' => Event::count(),
            'bookings' => Booking::count(),
            'users' => User::count(),
            'revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        $recentBookings = Booking::with(['user', 'event'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings'));
    }
// Покажем форму создания организатора
public function createOrganizer(Request $request)
{
    $users = User::whereDoesntHave('organizer')
               ->where('role', '!=', 'admin')
               ->get();

    $selectedUserId = $request->input('user_id');

    return view('components.admin.organizers.create', [
        'users' => $users,
        'selectedUserId' => $selectedUserId
    ]);
}

// Сохраним нового организатора
public function storeOrganizer(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,user_id',
        'organization_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'contact_person' => 'required|string|max:255',
        'contact_info' => 'required|string|max:255',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Обновляем роль пользователя
$user = User::find($validated['user_id']);
    $user->update(['role' => 'organizer']);

    // Создаем организатора
    $organizerData = [
        'user_id' => $validated['user_id'],
        'organization_name' => $validated['organization_name'],
        'description' => $validated['description'] ?? '',
        'contact_person' => $validated['contact_person'],
        'contact_info' => $validated['contact_info'],
        'is_verified' => true, // Админ создает - сразу верифицируем
    ];

    // Обработка логотипа
    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('organizers/logos', 'public');
        $organizerData['logo_url'] = $path;
    }

    Organizer::create($organizerData);

    return redirect()->route('admin.users.index')
        ->with('success', 'Организатор успешно создан');
}
    // Мероприятия
    public function eventsIndex()
    {
        $events = Event::latest()->paginate(15);
        return view('components.admin.events.index', compact('events'));
    }

    public function eventsCreate()
{
    $organizers = Organizer::all(); // Или другой запрос для получения организаторов
    return view('components.admin.events.create', compact('organizers'));
}

// public function eventsStore(Request $request)
// {
//     $validated = $request->validate([
//         'title' => 'required|max:255',
//         'start_datetime' => 'required|date',
//         'end_datetime' => 'nullable|date|after:start_datetime',
//         'location' => 'nullable|string',
//         'description' => 'nullable|string',
//         'organizer_id' => 'required|exists:organizers,organizer_id'
//     ]);

//     Event::create($validated);

//     return redirect()->route('admin.events.index')
//         ->with('success', 'Мероприятие создано');
// }

public function eventsStore(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:255',
        'start_datetime' => 'required|date',
        'organizer_id' => 'required|integer|exists:Organizers,organizer_id'
    ]);

    // Только обязательные поля
    $eventData = [
        'title' => $validated['title'],
        'start_datetime' => $validated['start_datetime'],
        'organizer_id' => $validated['organizer_id'],
        'category' => 'concert',
        'is_published' => 0,
        'is_free' => 0,
        'is_featured' => 0,
        'price' => 0,
        'is_booking' => 1,
        'link' => ''
    ];

    // Добавляем необязательные поля, если они есть
    if ($request->has('end_datetime')) {
        $eventData['end_datetime'] = $request->end_datetime;
    }


    try {
        Event::create($eventData);
        return redirect()->route('admin.events.index')
            ->with('success', 'Мероприятие создано');
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Ошибка: '.$e->getMessage());
    }
}
// public function eventsStore(Request $request)
// {
//     $validated = $request->validate([
//         'title' => 'required|max:255',
//         'booking_type' => 'required|in:general,seated',
//         'start_datetime' => 'required|date',
//          'end_datetime' => 'nullable|date|after:start_datetime',
//          'category' => 'concert',
//         'location' => 'nullable|string',
//         'description' => 'nullable|string',
//         'organizer_id' => 'required|exists:organizers,organizer_id',
//                 'is_published' => 0,
//         'is_free' => 0,
//         'is_featured' => 0,
//         'price' => 0,
//         'is_booking' => 1,
//         'link' => ''
//     ]);

//     $event = Event::create($validated);

//     if ($request->booking_type === 'seated' && $request->has('seats')) {
//         $this->createSeatsForEvent($event, $request->seats);
//     }

//     return redirect()->route('admin.events.index')
//         ->with('success', 'Мероприятие создано');
// }

protected function createSeatsForEvent(Event $event, array $seatsData)
{
    foreach ($seatsData as $seatStr) {
        [$zone, $row, $number] = explode(',', $seatStr);

        $event->seats()->create([
            'zone' => $zone,
            'row' => $row,
            'number' => $number,
            'price' => 1000,
        ]);
    }
}

    public function eventsEdit(Event $event)
    {
        return view('components.admin.events.edit', compact('event'));
    }

    public function eventsUpdate(Request $request, Event $event)
    {
        $event->update($request->validate([
            'title' => 'required|max:255',
            // другие правила валидации
        ]));

        return back()->with('success', 'Мероприятие обновлено');
    }

    public function eventsDestroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Мероприятие удалено');
    }

    // Бронирования
    public function bookingsIndex()
    {
        $bookings = Booking::with(['user', 'event'])
            ->latest()
            ->paginate(15);

        return view('components.admin.bookings.index', compact('bookings'));
    }

    public function bookingsShow(Booking $booking)
    {
        return view('components.admin.bookings.show', compact('booking'));
    }

    public function bookingsCancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Бронирование отменено');
    }

    public function bookingsDestroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Бронирование удалено');
    }

    // Пользователи
    public function usersIndex()
    {
        $users = User::latest()->paginate(15);
        return view('components.admin.users.index', compact('users'));
    }

    public function usersEdit(User $user)
    {
        return view('components.admin.users.edit', compact('user'));
    }

   public function usersUpdate(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$user->user_id.',user_id',
        'role' => 'required|in:user,admin'
    ]);

    $user->update($validated);

    return back()->with('success', 'Данные пользователя обновлены');
}

    public function usersDestroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Пользователь удален');
    }

    // Настройки
    public function settings()
    {
        return view('components.admin.settings');
    }
    public function updateSettings(Request $request)
    {
        // Логика обновления настроек
        return back()->with('success', 'Настройки обновлены');
    }
}
