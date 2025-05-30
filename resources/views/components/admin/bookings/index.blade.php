@extends('layouts.admin')

@section('title', 'Бронирования')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Бронирования</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Мероприятие</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr>
                   <td class="px-6 py-4 whitespace-nowrap">{{ $booking->id }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            {{ $booking->event?->title ?? 'Мероприятие удалено' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            {{ $booking->user?->name ?? 'Пользователь удален' }}
        </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">

                        @if($booking->status !== 'cancelled')
                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-yellow-500 hover:text-yellow-700">
                                Отменить
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить бронирование?')">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
