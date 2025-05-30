@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Места проведения мероприятий</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($venues as $venue)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <h3 class="font-bold text-lg mb-2">{{ $venue->location }}</h3>
                <a href="{{ route('venues.show', ['location' => $venue->location]) }}"
                   class="inline-flex items-center text-red-600 hover:text-red-800 mt-2">
                    Смотреть мероприятия
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-600">Нет данных о местах проведения</p>
            </div>
        @endforelse
    </div>

    @if($venues->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $venues->links() }}
        </div>
    @endif
</div>
@endsection
