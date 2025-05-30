@extends('layouts.app')

@section('content')
    <!-- Главный баннер -->
    <section class="mb-12">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded-lg overflow-hidden">
                @foreach ($featuredEvents as $key => $event)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        @if ($event->image)
                            <img src="{{ asset(path: 'images/' . ($event->poster ?? '1.jpg')) }}"
                                aria-checked=""class="d-block w-100" alt="{{ $event->title }}"
                                style="height: 400px; object-fit: cover;">
                        @else
                            <img src="{{ asset(path: 'images/' . ($event->poster ?? '1.jpg')) }}"
                                aria-checked=""class="d-block w-100" alt="{{ $event->title }}"
                                style="height: 400px; object-fit: cover;">
                        @endif
                        <div class="carousel-caption d-none d-md-block bg-black bg-opacity-50 p-4 rounded">
                            <h2 class="text-2xl font-bold">{{ $event->title }}</h2>
                            <p>
                                {{ $event->start_datetime->format('d M Y H:i') }}
                                @if ($event->venue)
                                    в {{ $event->venue->name }}
                                @endif
                            </p>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-danger mt-2">Подробнее</a>
                            <!-- @if (!$event->is_free)
                                <a href="#" class="btn btn-success mt-2">Купить билет</a>
                            @endif -->
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($featuredEvents->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </section>

    <!-- Ближайшие мероприятия -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ближайшие мероприятия</h2>
            <a href="{{ route('events.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                Все мероприятия <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($upcomingEvents as $event)
                @include('components.event-card', ['event' => $event])
            @endforeach
        </div>
    </section>

    <!-- Категории мероприятий -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Категории</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            @foreach ($categories as $category)
                <a href="#" class="bg-white p-4 rounded-lg shadow-sm text-center hover:bg-red-50 transition">
                    <div class="text-red-600 mb-2">
                        @php
                            // Выбираем иконку в зависимости от категории
                            $icon = match ($category) {
                                'Концерты' => 'fa-music',
                                'Театры' => 'fa-theater-masks',
                                'Выставки' => 'fa-paint-brush',
                                'Кино' => 'fa-film',
                                'Спорт' => 'fa-running',
                                'Фестивали' => 'fa-glass-cheers',
                                'Дети' => 'fa-child',
                                'Образование' => 'fa-graduation-cap',
                                default => 'fa-calendar-alt',
                            };
                        @endphp
                        <i class="fas {{ $icon }} text-2xl"></i>
                    </div>
                    <span class="font-medium">{{ $category }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Спецразделы -->
    <section class="mb-12">
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Сегодня в Каменске -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-day text-red-600 mr-2"></i> Сегодня в Каменске
            </h3>
            <div class="space-y-4">
                @forelse($todayEvents as $event)
                    <div class="border-b pb-3 last:border-0">
                        <h4 class="font-medium">{{ $event->title }}</h4>
                        <div class="text-sm text-gray-600">
                            {{ $event->start_datetime->format('H:i') }}
                            @if ($event->location)
                                • {{ $event->location }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Нет событий сегодня</div>
                @endforelse
            </div>
            <a href="{{ route('events.index', ['date' => 'today']) }}"
                class="mt-4 inline-block text-red-600 hover:text-red-800 text-sm font-medium">
                Все события сегодня <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Бесплатные мероприятия -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tag text-green-600 mr-2"></i> Бесплатно
            </h3>
            <div class="space-y-4">
                @forelse($freeEvents as $event)
                    <div class="border-b pb-3 last:border-0">
                        <h4 class="font-medium">{{ $event->title }}</h4>
                        <div class="text-sm text-gray-600">
                            {{ $event->start_datetime->format('d.m H:i') }}
                            @if ($event->location)
                                • {{ $event->location }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Нет бесплатных событий</div>
                @endforelse
            </div>
            <a href="{{ route('events.index', ['price' => 'free']) }}"
               class="mt-4 inline-block text-red-600 hover:text-red-800 text-sm font-medium">
                Все бесплатные события <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Скоро в городе -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-star text-yellow-500 mr-2"></i> Скоро в городе
            </h3>
            <div class="space-y-4">
                @forelse($featuredEvents as $event)
                    <div class="border-b pb-3 last:border-0">
                        <h4 class="font-medium">{{ $event->title }}</h4>
                        <div class="text-sm text-gray-600">
                            {{ $event->start_datetime->format('d.m.Y') }}
                            @if ($event->location)
                                • {{ $event->location }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Нет предстоящих событий</div>
                @endforelse
            </div>
            <a href="{{ route('events.index') }}"
                class="mt-4 inline-block text-red-600 hover:text-red-800 text-sm font-medium">
                Все анонсы <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</section>
    <!-- Новости и анонсы -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Новости и анонсы</h2>
            <a href="#" class="text-red-600 hover:text-red-800 font-medium">
                Все новости <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($news as $item)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="{{ asset('images/' . ($item->image ?? '1.jpg')) }}" alt="{{ $item->title }}"
                        class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-2">{{ $item->created_at->format('d.m.Y') }}</div>
                        <h3 class="font-bold text-lg mb-2">{{ $item->title }}</h3>
                        <p class="text-gray-600 mb-3 line-clamp-2">{{ $item->excerpt }}</p>
                        <a href="#" class="text-red-600 hover:text-red-800 font-medium">Читать далее...</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
