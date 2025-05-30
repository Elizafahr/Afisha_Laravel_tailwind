@extends('layouts.admin')

@section('title', 'Бронирование #'.$booking->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold">Бронирование #{{ $booking->id }}</h1>
            <span class="px-3 py-1 text-sm rounded-full
                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $booking->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Информация о мероприятии</h2>
                <p><strong>Название:</strong> {{ $booking->event->title }}</p>
                <p><strong>Дата:</strong> {{ $booking->event->start_datetime->format('d.m.Y H:i') }}</p>
                <p><strong>Место:</strong> {{ $booking->event->location }}</p>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-2">Информация о пользователе</h2>
                <p><strong>Имя:</strong> {{ $booking->user->name }}</p>
                <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                <p><strong>Телефон:</strong> {{ $booking->user->phone ?? 'Не указан' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Детали бронирования</h2>
            <p><strong>Дата бронирования:</strong> {{ $booking->created_at->format('d.m.Y H:i') }}</p>
            <p><strong>Количество билетов:</strong> {{ $booking->quantity }}</p>
            <p><strong>Сумма:</strong> {{ $booking->total_price }} ₽</p>
        </div>

        <div class="mt-6 flex space-x-4">
            @if($booking->status !== 'cancelled')
            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    Отменить бронирование
                </button>
            </form>
            @endif
            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" onclick="return confirm('Удалить бронирование?')">
                    Удалить бронирование
                </button>
            </form>
            <a href="{{ route('admin.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Назад к списку
            </a>
        </div>
    </div>
</div>
@endsection
