@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Ваше бронирование #{{ $booking->booking_id }}</h2>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <h4>Детали мероприятия</h4>
                            <p><strong>Название:</strong> {{ $booking->event?->title ?? 'Мероприятие недоступно' }}</p>
                            <p><strong>Дата:</strong>
                                {{ $booking->event?->start_datetime?->format('d.m.Y H:i') ?? 'Не указана' }}</p>
                            <p><strong>Место:</strong> {{ $booking->event?->location ?? 'Не указано' }}</p>
                        </div>

                        <div class="mb-4">
                            <h4>Детали бронирования</h4>
                            <p><strong>Статус:</strong>
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                    {{ $booking->status }}
                                </span>
                            </p>
                            <p><strong>Тип билета:</strong>
                                {{ $booking->ticket ? $booking->ticket->ticket_type : 'Общий вход' }}
                            </p>
                            @if ($booking->seat)
                                <p><strong>Место:</strong>
                                    {{ $booking->seat->zone }}, ряд {{ $booking->seat->seat_row }}, место
                                    {{ $booking->seat->seat_number }}
                                </p>
                            @endif
                            <p><strong>Количество:</strong> {{ $booking->quantity }}</p>
                            <p><strong>Общая стоимость:</strong> {{ $booking->total_price }} ₽</p>
                            <p><strong>Дата бронирования:</strong> {{ $booking->booking_date->format('d.m.Y H:i') }}</p>
                        </div>

                        @if (session('ticketNumbers'))
                            <div class="mb-4">
                                <h4>Номера ваших билетов</h4>
                                <ul>
                                    @foreach (session('ticketNumbers') as $number)
                                        <li>{{ $number }}</li>
                                    @endforeach
                                </ul>
                                <div class="alert alert-info mt-3">
                                    Сохраните эти номера. Они понадобятся для входа на мероприятие.
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2">

                            <a href=" " class="btn btn-outline-primary">
                                Вернуться к мероприятию
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
