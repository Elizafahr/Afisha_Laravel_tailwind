@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Основная информация о событии -->
                <div class="card mb-4">
                    @if ($event->image)
                        <img src="{{ asset(path: 'images/' . ($event->poster ?? '1.jpg')) }}"
                            aria-checked=""class="d-block w-100" alt="{{ $event->title }}"
                            style="height: 400px; object-fit: cover;">
                    @else
                        <img src="{{ asset(path: 'images/' . ($event->poster ?? '1.jpg')) }}"
                            aria-checked=""class="d-block w-100" alt="{{ $event->title }}"
                            style="height: 400px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <h1 class="card-title">{{ $event->title }}</h1>
                        <!-- Кнопка добавления в избранное -->
                        @auth
                            <form class="favorite-form"
                                action="{{ route(auth()->user()->hasFavorite($event->event_id) ?
                                 'events.favorite.remove' : 'events.favorite.add', $event) }}"
                                method="POST">
                                @csrf
                                @if (auth()->user()->hasFavorite($event->event_id))
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger favorite-btn">
                                        <i class="fas fa-heart"></i> В избранном
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-outline-secondary favorite-btn">
                                        <i class="far fa-heart"></i> В избранное
                                    </button>
                                @endif
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary" title="Необходимо войти">
                                <i class="far fa-heart"></i> В избранное
                            </a>
                        @endauth
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary">{{ $event->category->name ?? 'Без категории' }}</span>
                                @if ($event->is_free)
                                    <span class="badge bg-success">Бесплатно</span>
                                @else
                                    <span class="badge bg-warning text-dark">Платно</span>
                                @endif
                            </div>
                            <div class="text-muted">
                                <i class="far fa-calendar-alt"></i>
                                {{ $event->start_datetime->format('d.m.Y H:i') }}
                                @if ($event->end_datetime)
                                    - {{ $event->end_datetime->format('d.m.Y H:i') }}
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5><i class="fas fa-map-marker-alt"></i> Место проведения:</h5>
                            <p class="mb-1">
                                @if ($event->venue)
                                    <strong>{{ $event->venue->name }}</strong><br>
                                    {{ $event->venue->address }}
                                @else
                                    Место уточняется
                                @endif
                            </p>
                            @if ($event->venue && $event->venue->map_link)
                                <a href="{{ $event->venue->map_link }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary mt-2">
                                    Посмотреть на карте
                                </a>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5>Описание:</h5>
                            <p class="card-text">{!! nl2br(e($event->description)) !!}</p>
                        </div>

                        @if ($event->organizer)
                            <div class="mb-4">
                                <h5>Организатор:</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if ($event->organizer->logo_url)
                                                <img src="{{ $event->organizer->logo_url }}" class="rounded me-3"
                                                    width="60" height="60" style="object-fit: cover;" alt="Логотип организатора">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $event->organizer->organization_name }}</h6>
                                                @if ($event->organizer->is_verified) <span class="badge bg-success">Проверенный организатор</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($event->organizer->description)
                                            <p class="mb-2">{{ $event->organizer->description }}</p>
                                        @endif

                                        @if ($event->organizer->contact_person)
                                            <p class="mb-0"><strong>Контактное лицо:</strong>{{ $event->organizer->contact_person }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        @if (!$event->is_free)
                            @if ($event->is_booking)
                                <a href="{{ route('bookings.create', ['event' => $event->event_id]) }}"
                                    class="btn btn-primary btn-lg">
                                    Забронировать билет
                                </a>
                            @elseif($event->link)
                                <a href="{{ $event->link }}" class="btn btn-primary btn-lg" target="_blank">
                                    Купить билет
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                            Все события
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Боковая панель с дополнительной информацией -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white"><h5 class="mb-0">Детали события</h5></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Дата:</span><strong>{{ $event->start_datetime->format('d.m.Y') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Время:</span><strong>{{ $event->start_datetime->format('H:i') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Цена:</span>
                                <strong>
                                    @if ($event->is_free)Бесплатно
                                    @else{{ $event->price ?? 'Цена не указана' }} руб.
                                    @endif
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Возрастное ограничение:</span>
                                <strong>{{ $event->age_restriction ?? 'Нет' }}+</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Похожие события -->
                @if ($relatedEvents->isNotEmpty())
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Похожие события</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($relatedEvents as $relatedEvent)
                                <div class="mb-3">
                                    <a href="{{ route('events.show', $relatedEvent) }}" class="text-decoration-none">
                                        <div class="d-flex">
                                            @if ($relatedEvent->poster)
                                                <img src="{{ asset('images/' . $relatedEvent->poster) }}"
                                                    class="rounded me-3" width="80" height="60"
                                                    style="object-fit: cover;" alt="{{ $relatedEvent->title }}">
                                            @else
                                                <img src="https://via.placeholder.com/80x60?text={{ urlencode(substr($relatedEvent->title, 0, 10)) }}"
                                                    class="rounded me-3" width="80" height="60"
                                                    alt="{{ $relatedEvent->title }}">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($relatedEvent->title, 35) }}</h6>
                                                <small class="text-muted">
                                                    {{ $relatedEvent->start_datetime->format('d.m.Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.favorite-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    fetch(this.action, {
                            method: this.method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: this.method === 'DELETE' ? null : JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Обновляем кнопку
                                const newForm = data.is_favorite ?
                                    `<form class="favorite-form" action="{{ route('events.favorite.remove', $event) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger favorite-btn">
                                <i class="fas fa-heart"></i> В избранном
                            </button>
                        </form>` :
                                    `<form class="favorite-form" action="{{ route('events.favorite.add', $event) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary favorite-btn">
                                <i class="far fa-heart"></i> В избранное
                            </button>
                        </form>`;

                                form.outerHTML = newForm;

                                // Добавляем обработчик на новую кнопку
                                document.querySelector('.favorite-form').addEventListener(
                                    'submit', arguments.callee);

                                // Показываем уведомление
                                const alert = document.createElement('div');
                                alert.className =
                                    'alert alert-success position-fixed top-0 end-0 m-3';
                                alert.innerHTML = data.message;
                                document.body.appendChild(alert);
                                setTimeout(() => alert.remove(), 3000);
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Произошла ошибка');
                        });
                });
            });
        });
    </script>
@endsection
