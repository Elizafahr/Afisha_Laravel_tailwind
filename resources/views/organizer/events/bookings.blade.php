@extends('organizer.layout')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Активные бронирования</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID брони</th>
                            <th>Мероприятие</th>
                            <th>Пользователь</th>
                            <th>Детали</th>
                            <th>Статус</th>
                            <th>Общая стоимость</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->booking_id }}</td>
                            <td>
                                @if($booking->event)
                                    {{ $booking->event->title }}
                                @else
                                    Мероприятие удалено
                                @endif
                            </td>
                            <td>{{ $booking->user->username }}</td>
                            <td>
                                @if($booking->ticket)
                                    {{ $booking->ticket->ticket_type }} (x{{ $booking->quantity }})
                                @elseif($booking->seat)
                                    Место: {{ $booking->seat->zone }} - Ряд {{ $booking->seat->seat_row }}, {{ $booking->seat->seat_number }}
                                @else
                                    Общий вход (x{{ $booking->quantity }})
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if($booking->status == 'confirmed') bg-success
                                    @elseif($booking->status == 'pending') bg-warning text-dark
                                    @endif">
                                    {{ ucfirst($booking->status == 'confirmed' ? 'подтверждено' : 'ожидание') }}
                                </span>
                            </td>
                            <td>{{ number_format($booking->total_price, 2) }} ₽</td>
                            <td>{{ $booking->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <form action="{{ route('organizer.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Вы уверены, что хотите отменить это бронирование?')">
                                        Отменить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Активных бронирований не найдено</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
