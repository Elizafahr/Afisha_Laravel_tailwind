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
        // $activeBookings = $user->bookings->filter(function($booking) {
        //     // return $booking->event->end_datetime >= now();
        //     return $booking->all();
        // });

        $pastBookings = $user->bookings->filter(function($booking) {
           // return $booking->event->end_datetime < now();
            return $booking->all();
        });
 $activeBookings = $user->bookings()
        ->with(['event', 'seats'])
        ->whereIn('status', ['confirmed', 'pending'])
        ->whereHas('event', function($query) {
            $query->where('start_datetime', '>', now());
        })
        ->orderByDesc('created_at')
        ->get();
        return view('profile.show', [
            'user' => $user,
            'activeBookings' => $activeBookings,
            'pastBookings' => $pastBookings,
            'reviews' => $user->reviews,
            'favorites' => $user->favorites
        ]);
    }

    public function show($id)
{
    $user = User::with(['bookings.event', 'favorites.event', 'reviews.event'])->findOrFail($id);

    $activeBookings = $user->bookings()
        ->with(['event', 'seats'])
        ->whereIn('status', ['confirmed', 'pending'])
        ->whereHas('event', function($query) {
            $query->where('start_datetime', '>', now());
        })
        ->orderByDesc('created_at')
        ->get();

    $pastBookings = $user->bookings()
        ->with('event')
        ->whereIn('status', ['completed', 'cancelled'])
        ->orWhereHas('event', function($query) {
            $query->where('start_datetime', '<', now());
        })
        ->orderByDesc('created_at')
        ->get();

    $favorites = $user->favorites()->with('event')->get();
    $reviews = $user->reviews()->with('event')->get();

    return view('profile.show', compact(
        'user',
        'activeBookings',
        'pastBookings',
        'favorites',
        'reviews'
    ));
}
}
