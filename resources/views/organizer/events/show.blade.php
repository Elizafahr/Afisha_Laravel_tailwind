@extends('organizer.layout')

@section('title', $event->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>{{ $event->title }}</h5>
                <div class="btn-group">
                    <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm btn-outline-secondary">Редактировать</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Дата и время:</strong>
                        <p>{{ $event->start_datetime->format('d.m.Y H:i') }} - {{ $event->end_datetime->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Место:</strong>
                        <p>{{ $event->location }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Цена:</strong>
                        <p>{{ $event->is_free ? 'Бесплатно' : $event->price.' ₽' }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Описание:</strong>
                    <p>{{ $event->description }}</p>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Категория:</strong>
                        <p>{{ ucfirst($event->category) }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Возрастное ограничение:</strong>
                        <p>{{ $event->age_restriction ? $event->age_restriction.'+' : 'Нет' }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Статус:</strong>
                        <p>
                            @if($event->is_published)
                                <span class="badge bg-success">Опубликовано</span>
                            @else
                                <span class="badge bg-warning">Черновик</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Бронирования</h5>
            </div>
            <div class="card-body">
                @if($event->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Дата</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_id }}</td>
                                    <td>{{ $booking->booking_date->format('d.m.Y H:i') }}</td>
                                    <td>{{ $booking->quantity }}</td>
                                    <td>{{ $booking->total_price }} ₽</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                                            {{ $booking->status === 'confirmed' ? 'Подтверждено' : 'Ожидание' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Нет бронирований</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Постер</h5>
            </div>
            <div class="card-body text-center">
                @if($event->poster_url)
                    <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="img-fluid rounded">
                @else
                    <p>Постер не загружен</p>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Типы билетов</h5>
                <a href="{{ route('organizer.events.tickets', $event) }}" class="btn btn-sm btn-outline-primary">Управление</a>
            </div>
            <div class="card-body">
                @if($event->tickets->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($event->tickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $ticket->ticket_type }}
                            <span class="badge bg-primary rounded-pill">{{ $ticket->price }} ₽</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p>Билеты не настроены</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Места</h5>
                <a href="{{ route('organizer.events.seats', $event) }}" class="btn btn-sm btn-outline-primary">Управление</a>
            </div>
            <div class="card-body">
                @if($event->seats->count() > 0)
                    <p>Всего мест: {{ $event->seats->count() }}</p>
                    <p>Зоны: {{ $event->seats->groupBy('zone')->count() }}</p>
                @else
                    <p>Места не настроены</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
