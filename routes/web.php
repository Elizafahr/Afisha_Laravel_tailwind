<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\OrganizerEventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты для мероприятий
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::get('/events/create', 'create')->name('events.create');
    Route::get('/events/{event}', 'show')->name('events.show');
    Route::get('/events/category/{category}', 'category')->name('events.category');
    Route::get('/events/today', 'today')->name('events.today');
    Route::get('/events/free', 'free')->name('events.free');
    Route::get('/events/featured', 'featured')->name('events.featured');
    Route::get('/events/upcoming', 'upcoming')->name('events.upcoming');

    Route::post('/events/{event}/favorite', 'store')
        ->name('events.favorite.add')
        ->middleware('auth');

    Route::delete('/events/{event}/favorite', 'destroy')
        ->name('events.favorite.remove')
        ->middleware('auth');

    Route::post('/add-to-favorites/{eventId}', 'addToFavorites')->middleware('auth');
    Route::post('/remove-from-favorites/{eventId}', 'removeFromFavorites')->name('remove-from-favorites');

    Route::get('/venues', 'venues')->name('venues.index');
    Route::get('/venues/{location}', 'venueEvents')->name('venues.show');
});

// Контакты
Route::controller(ContactController::class)->group(function () {
    Route::get('/contacts', 'index')->name('contacts.index');
    Route::post('/contacts', 'submit')->name('contacts.submit');
});

// Организаторы
Route::prefix('organizers')->controller(OrganizerController::class)->group(function () {
    Route::get('/', 'index')->name('organizers.index');
    Route::get('/{organizer}', 'show')->name('organizers.show');
    Route::post('/apply', 'apply')->name('organizers.apply');
});

// Новости
Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'index')->name('news.index');
    Route::get('/news/{news}', 'show')->name('news.show');
});

// Аутентификация
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
// Профиль пользователя
Route::get('/profile/{id}', [UserController::class, 'indexUser'])->name('profile.show');
// Страница для организаторов
Route::get('/for-organizers', function () {
    return view('organizers');
})->name('for-organizers');
// Бронирования
Route::middleware('auth')->controller(BookingController::class)->group(function () {
    Route::get('/events/{event}/book', 'create')->name('bookings.create');
     Route::post('/events/{event}/bookings', 'store')->name('bookings.store');

    Route::post('/events/{event}/book-seats', [BookingController::class, 'bookSeats'])
        ->name('bookings.bookSeats')
        ->middleware('auth');
    Route::get('/bookings/{booking}', 'show')->name('bookings.show');
});
// Админка
Route::prefix('admin')->middleware('auth')->controller(AdminController::class)->group(function () {
    // Главная панель
    Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
    // Управление мероприятиями
    Route::get('/events', 'eventsIndex')->name('admin.events.index');
    Route::get('/events/create', 'eventsCreate')->name('admin.events.create');
    Route::get('/events/{event}/edit', 'eventsEdit')->name('admin.events.edit');
    Route::put('/events/{event}', 'eventsUpdate')->name('admin.events.update');
    Route::delete('/events/{event}', 'eventsDestroy')->name('admin.events.destroy');
    // Управление бронированиями
    Route::get('/bookings', 'bookingsIndex')->name('admin.bookings.index');
    Route::get('/bookings/{booking}', 'bookingsShow')->name('admin.bookings.show');
    Route::delete('/bookings/{booking}', 'bookingsDestroy')->name('admin.bookings.destroy');
    Route::post('/bookings/{booking}/cancel', 'bookingsCancel')->name('admin.bookings.cancel');

    // Управление пользователями
    Route::get('/users', 'usersIndex')->name('admin.users.index');
    Route::get('/users/{user}/edit', 'usersEdit')->name('admin.users.edit');
    Route::put('/users/{user}', 'usersUpdate')->name('admin.users.update')->where('user', '[0-9]+');
    Route::delete('/users/{user}', 'usersDestroy')->name('admin.users.destroy');
    Route::get('/organizers/create', 'createOrganizer')->name('admin.organizers.create');
    Route::post('/organizers', 'storeOrganizer')->name('admin.organizers.store');

    // Настройки системы
    Route::get('/settings', 'settings')->name('admin.settings');
    Route::post('/settings', 'updateSettings')->name('admin.settings.update');
});
Route::post('/events', [AdminEventController::class, 'store'])->name('admin.events.store');
// Маршруты организатора
Route::prefix('organizer')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('organizer.dashboard');
    //форма создания мероприятия
    Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('organizer.events.create');
    Route::post('/events/store', [OrganizerEventController::class, 'store'])->name('organizer.events.store');
    Route::post('/events/show', [OrganizerEventController::class, 'show'])->name('organizer.events.show');
    Route::get('/events', [OrganizerEventController::class, 'index'])->name('organizer.events.index');
    Route::get('events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('organizer.events.edit');
    Route::delete('events/{event}', [OrganizerEventController::class, 'destroy'])->name('organizer.events.destroy');
    Route::put('events/{event}', [OrganizerEventController::class, 'update'])->name('organizer.events.update');
    Route::get('events/{event}/tickets', [OrganizerEventController::class, 'tickets'])->name('organizer.events.tickets');
    Route::post('events/{event}/tickets', [OrganizerEventController::class, 'storeTickets']);
    Route::get('events/{event}/seats', [OrganizerEventController::class, 'seats'])->name('organizer.events.seats');
    Route::post('events/{event}/seats', [OrganizerEventController::class, 'storeSeats']);
    Route::get('bookings', [OrganizerController::class, 'bookings'])->name('organizer.bookings');
    Route::get('reviews', [OrganizerController::class, 'reviews'])->name('organizer.reviews');
    Route::get('bookings', [OrganizerController::class, 'bookings'])->name('organizer.bookings');
    Route::get('events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('organizer.events.edit');
    Route::delete('events/{event}', [OrganizerEventController::class, 'destroy'])->name('organizer.events.destroy');
    Route::put('events/{event}', [OrganizerEventController::class, 'update'])->name('organizer.events.update');
});
Route::get('events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('organizer.events.edit');
Route::put('events/{event}', [OrganizerEventController::class, 'update'])->name('organizer.events.update');
