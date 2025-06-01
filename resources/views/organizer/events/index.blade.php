@extends('organizer.layout')

@section('title', 'Мои мероприятия')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Мои мероприятия</h1>
        <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Создать мероприятие
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($events->count() === 0)
        <div class="alert alert-info">
            У вас пока нет мероприятий. Создайте первое мероприятие!
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Дата</th>
                        <th>Место</th>
                        <th>Бронирования</th>
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
                        <td>{{ $event->bookings_count ?? 0 }}</td>
                        <td>
                            <span class="badge {{ $event->is_published ? 'bg-success' : 'bg-secondary' }}">
                                {{ $event->is_published ? 'Опубликовано' : 'Черновик' }}
                            </span>
                        </td>
                        <td class="d-flex gap-2">
                            {{-- <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-sm btn-info" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a> --}}
                            <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm btn-warning" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                         <form action="{{ route('organizer.events.destroy', $event) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger"
            onclick="return confirm('Вы уверены?')">
        <i class="fas fa-trash"></i> Удалить
    </button>
</form>
                            {{-- <form action="{{ route('organizer.events.toggle-publish', $event) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $event->is_published ? 'btn-secondary' : 'btn-success' }}" title="{{ $event->is_published ? 'Снять с публикации' : 'Опубликовать' }}">
                                    <i class="fas {{ $event->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
