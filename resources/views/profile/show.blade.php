@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Левая колонка - информация о пользователе -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <div class="flex flex-col items-center mb-6">
                    <!-- Аватар пользователя -->
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center mb-4 overflow-hidden">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800">{{ $user->username }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>

                    @if($user->phone)
                        <div class="flex items-center mt-2 text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $user->phone }}</span>
                        </div>
                    @endif
                </div>

                <!-- Статистика -->
                <div class="grid grid-cols-3 gap-2 text-center border-t pt-4">
                    <div>
                        <div class="text-lg font-bold">{{ $user->bookings->count() }}</div>
                        <div class="text-sm text-gray-600">Бронирований</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold">{{ $user->reviews->count() }}</div>
                        <div class="text-sm text-gray-600">Отзывов</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold">{{ $user->favorites->count() }}</div>
                        <div class="text-sm text-gray-600">В избранном</div>
                    </div>
                </div>

                <!-- Редактирование профиля (только для владельца) -->
                @auth
                    @if(auth()->id() == $user->id)
                        <div class="mt-6">
                            <a href="{{ route('profile.edit') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded transition">
                                Редактировать профиль
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Правая колонка - активность пользователя -->
        <div class="md:w-2/3 space-y-8">
            <!-- Активные бронирования -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Активные бронирования ({{ $activeBookings->count() }})
                </h2>

                
            </div>

            <!-- Прошедшие бронирования -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Прошедшие события ({{ $pastBookings->count() }})
                </h2>

             
            </div>

            <!-- Отзывы -->
            <!-- <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Отзывы ({{ $reviews->count() }})
                </h2>

                @if($reviews->isEmpty())
                    <p class="text-gray-500">Пользователь еще не оставлял отзывов</p>
                @else
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="border-b pb-4 last:border-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">{{ $review->event->title }}</h3>
                                        <div class="flex items-center mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="text-sm text-gray-600 ml-2">{{ $review->created_at->format('d.m.Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div> -->

            <!-- Избранное -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Избранное ({{ $favorites->count() }})
                </h2>

                @if($favorites->isEmpty())
                    <p class="text-gray-500">В избранном пока ничего нет</p>
                @else
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($favorites as $favorite)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-medium">{{ $favorite->event->title }}</h3>
                                <div class="text-sm text-gray-600 mt-1">
                                    {{ $favorite->event->start_datetime->format('d.m.Y H:i') }}
                                </div>
                                <a href="{{ route('events.show', $favorite->event) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mt-2">
                                    Подробнее
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
