<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Страница пользователя
    public function indexUser($id)
    {
        $user = User::with(['bookings.event', 'reviews.event', 'favorites.event'])
            ->findOrFail($id);

        // Разделяем бронирования на активные и прошедшие
        $activeBookings = $user->bookings->filter(function($booking) {
            // return $booking->event->end_datetime >= now();
            return $booking->all();
        });

        $pastBookings = $user->bookings->filter(function($booking) {
           // return $booking->event->end_datetime < now();
            return $booking->all();
        });

        return view('profile.show', [
            'user' => $user,
            'activeBookings' => $activeBookings,
            'pastBookings' => $pastBookings,
            'reviews' => $user->reviews,
            'favorites' => $user->favorites
        ]);
    }
}
