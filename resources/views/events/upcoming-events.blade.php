@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Заголовок -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Анонсы событий</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">{{ $events->total() }} анонсов</span>
            <div class="relative">
                <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-red-500">
                    <option>Сортировка</option>
                    <option>По дате</option>
                    <option>По популярности</option>
                    <option>По рейтингу</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="bg-white rounded-lg shadow p-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Категория</label>
                <select id="category" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Период</label>
                <select id="date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Ближайшие события</option>
                    <option>На этой неделе</option>
                    <option>В этом месяце</option>
                    <option>В этом году</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Тип события</label>
                <select id="type" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Все типы</option>
                    <option>Концерты</option>
                    <option>Выставки</option>
                    <option>Фестивали</option>
                    <option>Спорт</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                    Применить
                </button>
            </div>
        </div>
    </div>

    <!-- Список анонсов -->
    <div class="space-y-6 mb-8">
        @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="md:flex">
                    <div class="md:flex-shrink-0 md:w-1/3">
                        <img class="h-48 w-full object-cover md:h-full"
                             src="{{ $event->image ? asset('storage/'.$event->image) : asset('images/default-event.jpg') }}"
                             alt="{{ $event->title }}">
                    </div>
                    <div class="p-6 md:w-2/3">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="uppercase tracking-wide text-sm text-red-600 font-semibold">
                                    {{ $event->category->name }}
                                </div>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ $event->title }}</h3>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                Анонс
                            </span>
                        </div>

                        <div class="mt-2 flex items-center text-gray-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->start_date->format('d.m.Y') }} в {{ $event->start_time->format('H:i') }}
                        </div>

                        <div class="mt-1 flex items-center text-gray-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $event->venue->name ?? 'Место не указано' }}
                        </div>

                        <p class="mt-3 text-gray-600 line-clamp-2">
                            {{ $event->description }}
                        </p>

                        <div class="mt-4 flex justify-between items-center">
                            <span class="font-bold text-gray-900">
                                @if($event->is_free)
                                    Бесплатно
                                @else
                                    от {{ $event->min_price }} ₽
                                @endif
                            </span>
                            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium">
                                Подробнее
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Нет анонсов событий</h3>
                <p class="mt-1 text-gray-500">Следите за обновлениями, скоро появятся новые анонсы.</p>
            </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    @if($events->hasPages())
        <div class="flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
