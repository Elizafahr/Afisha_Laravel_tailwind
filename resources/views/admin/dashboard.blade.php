@extends('layouts.admin')

@section('title', 'Админ-панель')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 font-medium">Мероприятия</h3>
            <p class="text-3xl font-bold">{{ $stats['events'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 font-medium">Бронирования</h3>
            <p class="text-3xl font-bold">{{ $stats['bookings'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 font-medium">Пользователи</h3>
            <p class="text-3xl font-bold">{{ $stats['users'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 font-medium">Выручка</h3>
            <p class="text-3xl font-bold">{{ number_format($stats['revenue'], 2) }} ₽</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Последние бронирования</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Мероприятие</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Сумма</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>
                                @if ($booking->event)
                                    {{ $booking->event->title }}
                                @else
                                    <span class="text-gray-400">Мероприятие удалено</span>
                                @endif
                            </td>
                            <td>
                                @if ($booking->user)
                                    {{ $booking->user->name }}
                                @else
                                    <span class="text-gray-400">Пользователь удален</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->total_price }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-sm rounded-full
                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
