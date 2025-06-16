<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $recentBookings = Booking::all();
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

    public function eventsStore(Request $request)
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


        return redirect()->route('admin.dashboard')
            ->with('success', 'Мероприятие успешно создано!');
    }
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
        $organizers = Organizer::all(); // Или любой другой запрос
        return view('components.admin.events.edit', [
            'event' =>  $event,
            'organizers' =>  $organizers
        ]);
    }
//     public function eventsUpdate(Request $request, Event $event)

//     {
//          $validated = $request->validate([
//             'title' => 'required|string|max:255',
//             'description' => 'required|string',
//             'category' => 'required|in:concert,festival,exhibition,theater',
//             'start_datetime' => 'required|date',
//             'end_datetime' => 'required|date|after:start_datetime',
//             'location' => 'required|string',
//             'age_restriction' => 'nullable|integer|min:0',
//             'poster_url' => 'nullable|url',
//             'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//             'is_free' => 'nullable|in:0,1',
//             'price' => 'nullable|numeric|min:0|required_if:is_free,0',
//             'is_published' => 'nullable|in:0,1',
//             'is_booking' => 'nullable|in:0,1',
//             'booking_type' => 'required|in:seated,general',
//             'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
//             'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
//             'link' => 'nullable|url',
//             'organizer_id' => 'required|exists:organizers,organizer_id',
//         ]);

//         // Обработка постера
//         $posterUrl = $validated['poster_url'] ?? $event->poster_url;
//  // Удаление постера если отмечено
//         // if ($request->has('remove_poster') && $request->remove_poster) {
//         //     if ($event->poster && strpos($event->poster, 'images/') === 0) {
//         //         $oldFilePath = public_path($event->poster);
//         //         if (file_exists($oldFilePath)) {
//         //             unlink($oldFilePath);
//         //         }
//         //     }
//         //     $posterUrl = null;
//         // }

//         // Загрузка нового постера
//         // if ($request->hasFile('poster_file')) {
//         //     // Удаляем старый файл если он существует

//         //     $file = $request->file('poster_file');
//         //     $fileName = time() . '_' . $file->getClientOriginalName();
//         //     $file->move(public_path('images'), $fileName);
//         //     $posterUrl = $fileName; // Сохраняем только имя файла
//         // }



//         // Обновляем данные мероприятия
//         $event->update([
//             'title' => $validated['title'],
//             'description' => $validated['description'],
//             'category' => $validated['category'],
//             'start_datetime' => $validated['start_datetime'],
//             'end_datetime' => $validated['end_datetime'],
//             'location' => $validated['location'],
//             'age_restriction' => $validated['age_restriction'] ?? null,
//             'poster' => $posterUrl,
//             'is_free' => $validated['is_free'] ?? 0,
//             'price' => $validated['is_free'] ? 0 : ($validated['price'] ?? 0),
//             'is_published' => $validated['is_published'] ?? 0,
//             'is_booking' => $validated['is_booking'] ?? 1,
//             'booking_type' => $validated['booking_type'],
//             'link' => $validated['link'] ?? null,
//             'organizer_id' => $validated['organizer_id'],
//         ]);

//         // Если тип бронирования изменился на seated и указаны ряды/колонки
//         if (
//             $validated['booking_type'] === 'seated' &&
//             ($event->wasChanged('booking_type') || $event->wasChanged('rows') || $event->wasChanged('columns'))
//         ) {

//             // Удаляем старые места
//             DB::table('Seats')->where('event_id', $event->event_id)->delete();

//             // Создаем новые места
//             $this->generateSeats($event, $validated['rows'], $validated['columns']);
//         }

//         return redirect()->route('admin.events.index')
//             ->with('success', 'Мероприятие успешно обновлено!');}
public function eventsUpdate(Request $request, Event $event)
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
        'is_free' => 'nullable|in:0,1',
        'price' => 'nullable|numeric|min:0|required_if:is_free,0',
        'is_published' => 'nullable|in:0,1',
        'is_booking' => 'nullable|in:0,1',
        'booking_type' => 'required|in:seated,general',
        'rows' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'columns' => 'nullable|integer|min:1|required_if:booking_type,seated',
        'link' => 'nullable|url',
        'organizer_id' => 'required|exists:organizers,organizer_id',
    ]);

    // Инициализируем переменную для постера
    $poster = $event->poster;

    // Обработка удаления постера
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
    if ($request->hasFile('poster_file')) {
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
    // Если указан URL и не отмечено удаление
    elseif ($request->filled('poster_url') && !$request->has('remove_poster')) {
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
        'poster' => $poster, // Используем текущее значение (новое или старое)
        'is_free' => $validated['is_free'] ?? 0,
        'price' => $validated['is_free'] ? 0 : ($validated['price'] ?? 0),
        'is_published' => $validated['is_published'] ?? 0,
        'is_booking' => $validated['is_booking'] ?? 1,
        'booking_type' => $validated['booking_type'],
        'link' => $validated['link'] ?? null,
        'organizer_id' => $validated['organizer_id'],
    ]);

    // Обработка мест для сидячих мероприятий
    if ($validated['booking_type'] === 'seated' &&
        ($event->wasChanged('booking_type') || $event->wasChanged('rows') || $event->wasChanged('columns'))) {

        DB::table('Seats')->where('event_id', $event->event_id)->delete();
        $this->generateSeats($event, $validated['rows'], $validated['columns']);
    }

    return redirect()->route('admin.events.index')
        ->with('success', 'Мероприятие успешно обновлено!');
}
    // Бронирования
    public function bookingsIndex()
    {
        $bookings = Booking::all(); // correct
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
    public function updateBookingStatus(Request $request, Booking $booking)
{
    $validated = $request->validate([
        'status' => 'required|in:pending,confirmed,cancelled,completed'
    ]);

    $booking->update(['status' => $validated['status']]);

    return back()->with('success', 'Статус бронирования обновлен');
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
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
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
        return back()->with('success', 'Настройки обновлены');
    }
    // Изменение статуса организатора
    public function updateOrganizerStatus(Request $request, Organizer $organizer)
    {
        $validated = $request->validate([
            'status' => 'required|boolean'
        ]);

        $organizer->update(['is_verified' => $validated['status']]);

        return back()->with('success', 'Статус организатора обновлен');
    }
}
