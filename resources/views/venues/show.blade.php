@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Мероприятия в {{ $location }}</h1>
        <a href="{{ route('venues.index') }}" class="text-red-600 hover:text-red-800">
            ← Все места
        </a>
    </div>

    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                @include('components.event-card', ['event' => $event])
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-gray-600 mb-4">Нет предстоящих мероприятий в "{{ $location }}"</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('venues.index') }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                    ← Все места
                </a>
                <a href="{{ route('events.index') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Все мероприятия →
                </a>
            </div>
        </div>
    @endif

    @if($events->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
