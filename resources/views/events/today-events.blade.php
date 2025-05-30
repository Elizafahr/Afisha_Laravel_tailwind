@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Заголовок -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">События сегодня, {{ now()->format('d.m.Y') }}</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">{{ $events->total() }} событий</span>
            <div class="relative">
                <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-red-500">
                    <option>Сортировка</option>
                    <option>По времени</option>
                    <option>По популярности</option>
                    <option>По цене</option>
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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="time">Время</label>
                <select id="time" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Любое время</option>
                    <option>Утро (6:00-12:00)</option>
                    <option>День (12:00-18:00)</option>
                    <option>Вечер (18:00-24:00)</option>
                    <option>Ночь (0:00-6:00)</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Местоположение</label>
                <select id="location" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Весь город</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                    Применить
                </button>
            </div>
        </div>
    </div>

    <!-- Список событий -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="relative">
                    <img src="{{ $event->image ? asset('storage/'.$event->image) : asset('images/default-event.jpg') }}"
                         alt="{{ $event->title }}"
                         class="w-full h-48 object-cover">
                    <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                        {{ $event->start_time->format('H:i') }}
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-xl mb-2">{{ $event->title }}</h3>
                    <div class="flex items-center text-gray-600 text-sm mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->venue->name ?? 'Место не указано' }}
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="font-bold text-gray-900">
                            @if($event->is_free)
                                Бесплатно
                            @else
                                от {{ $event->min_price }} ₽
                            @endif
                        </span>
                        <a href="{{ route('events.show', $event) }}" class="text-red-600 hover:text-red-800 font-medium">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Сегодня нет событий</h3>
                <p class="mt-1 text-gray-500">Попробуйте посмотреть события на другую дату.</p>
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
