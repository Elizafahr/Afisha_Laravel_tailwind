{{-- @extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Заголовок -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Бесплатные события</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600">{{ $events->total() }} событий</span>
            <div class="relative">
                <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-red-500">
                    <option>Сортировка</option>
                    <option>По дате</option>
                    <option>По популярности</option>
                    <option>По отзывам</option>
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
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Дата</label>
                <select id="date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-red-500">
                    <option>Любая дата</option>
                    <option>Сегодня</option>
                    <option>Завтра</option>
                    <option>На этой неделе</option>
                    <option>В этом месяце</option>
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
                    <div class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">
                        Бесплатно
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-xl">{{ $event->title }}</h3>
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            {{ $event->category->name }}
                        </span>
                    </div>
                    <div class="flex items-center text-gray-600 text-sm mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $event->start_date->format('d.m.Y') }} в {{ $event->start_time->format('H:i') }}
                    </div>
                    <div class="flex items-center text-gray-600 text-sm mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->venue->name ?? 'Место не указано' }}
                    </div>
                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium">
                        Подробнее
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Нет бесплатных событий</h3>
                <p class="mt-1 text-gray-500">Попробуйте изменить параметры поиска или посмотрите платные мероприятия.</p>
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
@endsection --}}

@extends('events.list')
