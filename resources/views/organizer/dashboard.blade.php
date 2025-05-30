@extends('organizer.layout')

@section('title', 'Главная панель')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Мероприятий</h5>
                <p class="card-text display-4">{{ $events->total() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Бронирований</h5>
                <p class="card-text display-4">{{ $totalBookings }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Организация</h5>
                <p class="card-text">{{ $organizer->organization_name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Последние мероприятия</h5>
        <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">Создать мероприятие</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Дата</th>
                        <th>Место</th>
                        <th>Бронирований</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->start_datetime->format('d.m.Y H:i') }}</td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->bookings_count }}</td>
                        <td>
                            @if($event->is_published)
                                <span class="badge bg-success">Опубликовано</span>
                            @else
                                <span class="badge bg-warning">Черновик</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $events->links() }}
    </div>
</div>
@endsection
