<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
    <!-- Изображение мероприятия -->{{-- ?? 'https://via.placeholder.com/300x200?text=Каменск-События' }}" --}}
    <div class="relative h-48 overflow-hidden">
        <img src="{{ asset(path: 'images/' . ($event->poster ?? '1.jpg')) }}"        alt="{{ $event->title }}"
        class="w-full h-full object-cover">
        @if($event->is_free)
            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                Бесплатно
            </span>
        @endif
    </div>

    <!-- Контент карточки -->
    <div class="p-4">
        <div class="flex justify-between items-start">
            <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $event->title }}</h3>
            @if($event->age_restriction > 0)
                <span class="bg-gray-200 text-gray-800 text-xs px-2 py-1 rounded">
                    {{ $event->age_restriction }}+
                </span>
            @endif
        </div>

        <div class="flex items-center text-gray-600 text-sm mb-2">
            <i class="far fa-calendar-alt mr-2"></i>
            {{ $event->start_datetime->format('d.m.Y H:i') }}
        </div>

        <div class="flex items-center text-gray-600 text-sm mb-3">
            <i class="fas fa-map-marker-alt mr-2"></i>
            {{ $event->location }}
        </div>

        <div class="flex justify-between items-center">
            @if(!$event->is_free)
                <span class="text-red-600 font-bold">{{ $event->price }} ₽</span>
            @else
                <span class="text-green-600 font-bold">Бесплатно</span>
            @endif

            <a href="{{ route('events.show', $event) }}" class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md text-sm transition">

                 Подробнее
             </a>
             {{-- <a href="{{ route('events.show', $event->id) }}"
                class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded-md text-sm transition">
                 Подробнее
             </a> --}}
        </div>
    </div>
</div>
