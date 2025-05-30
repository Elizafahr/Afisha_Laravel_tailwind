@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <form method="GET" action="{{ route('events.index') }}" id="filter-form">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Афиша мероприятий</h1>

            <div class="flex space-x-4">
                <!-- Фильтр по категории -->
                <div class="relative">
                    <select name="category" class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Фильтр по дате -->
                <div class="relative">
                    <select name="date" class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Любая дата</option>
                        <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Сегодня</option>
                        <option value="tomorrow" {{ request('date') == 'tomorrow' ? 'selected' : '' }}>Завтра</option>
                        <option value="weekend" {{ request('date') == 'weekend' ? 'selected' : '' }}>На выходных</option>
                        <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>В этом месяце</option>
                    </select>
                </div>

                <!-- Фильтр по цене -->
                <div class="relative">
                    <select name="price" class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">Любая цена</option>
                        <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Бесплатные</option>
                        <option value="0-500" {{ request('price') == '0-500' ? 'selected' : '' }}>До 500 ₽</option>
                        <option value="500-1000" {{ request('price') == '500-1000' ? 'selected' : '' }}>500-1000 ₽</option>
                        <option value="1000-2000" {{ request('price') == '1000-2000' ? 'selected' : '' }}>1000-2000 ₽</option>
                        <option value="2000+" {{ request('price') == '2000+' ? 'selected' : '' }}>От 2000 ₽</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
    <!-- Баннер с акцией или важным событием -->
   @if($featuredEvent)
    <div class="bg-red-600 text-white rounded-lg p-6 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800 opacity-90"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center">
            <div class="md:w-2/3 mb-4 md:mb-0 md:pr-8">
                <h2 class="text-2xl font-bold mb-2">Главное событие недели</h2>
                <h3 class="text-xl font-semibold mb-3">{{ $featuredEvent->title }}</h3>
                <p class="mb-4">{{ $featuredEvent->start_datetime->format('d M Y, H:i') }} • {{ $featuredEvent->venue->name ?? $featuredEvent->location }}</p>
                <a href="{{ route('events.show', $featuredEvent) }}" class="inline-block bg-white text-red-600 px-6 py-2 rounded-md font-medium hover:bg-gray-100 transition">Подробнее</a>
            </div>
            <div class="md:w-1/3">
                <img src="{{ asset('images/' . ($featuredEvent->poster ?? '1.jpg')) }}" alt="{{ $featuredEvent->title }}" class="rounded-lg shadow-md w-full h-48 object-cover">
            </div>
        </div>
    </div>
@endif

    <!-- Список мероприятий -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($events as $event)
            @include('components.event-card', ['event' => $event])
        @empty
            <div class="col-span-full text-center py-12">
                <h3 class="text-xl font-medium text-gray-600">Нет мероприятий по выбранным критериям</h3>
                <p class="mt-2 text-gray-500">Попробуйте изменить параметры фильтрации</p>
            </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    @if($events->hasPages())
    <div class="flex justify-center mt-8">
        {{ $events->appends(request()->query())->links() }}
    </div>
    @endif

    <!-- Специальные разделы -->
<div class="mt-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Специальные подборки</h2>

    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('events.index', ['date' => 'today']) }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex items-center">
            <div class="bg-red-100 p-3 rounded-full mr-4">
                <i class="fas fa-calendar-day text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Сегодня в городе</h3>
                <p class="text-gray-600 text-sm mt-1">{{ $todayCount }} мероприятий</p>
            </div>
        </a>

        <a href="{{ route('events.index', ['price' => 'free']) }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-tag text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Бесплатные мероприятия</h3>
                <p class="text-gray-600 text-sm mt-1">{{ $freeCount }} бесплатных событий</p>
            </div>
        </a>

        <a href="{{ route('events.index', ['featured' => true]) }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <i class="fas fa-star text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Рекомендуем посетить</h3>
                <p class="text-gray-600 text-sm mt-1">{{ $featuredCount }} специальных подборок</p>
            </div>
        </a>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filter-form');
        const selects = form.querySelectorAll('select');

        selects.forEach(select => {
            select.addEventListener('change', function() {
                form.submit();
            });
        });
    });
    </script>


@endsection
