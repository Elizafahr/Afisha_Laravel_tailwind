@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Пользователи</h1>

        {{-- @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif --}}

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Роль</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"> </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->user_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->username ?? 'Не указано' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($user->role === 'admin')
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Администратор</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Пользователь</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                {{-- <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 hover:text-blue-700"
                                    title="Редактировать">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a> --}}
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Удалить"
                                        onclick="return confirm('Удалить пользователя?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if ($user->role !== 'admin' && !$user->organizer)
                                    {{-- Кнопка "Сделать организатором" может быть здесь --}}
                                @elseif($user->organizer)
                                    <div class="flex flex-col space-y-2">
                                        <!-- Бейдж организатора -->
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-user-tie mr-1"></i> Организатор
                                            </span>

                                            <!-- Статус верификации -->
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->organizer->is_verified ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $user->organizer->is_verified ? '✅ Верифицирован' : '⏳ На проверке' }}
                                            </span>
                                        </div>

                                        <!-- Кнопки управления верификацией -->
                                        @if (!$user->organizer->is_verified)
                                            <form action="{{ route('admin.organizers.status', $user->organizer) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit"
                                                    class="inline-flex items-center text-sm text-green-600 hover:text-green-900 transition-colors"
                                                    title="Верифицировать">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Подтвердить
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.organizers.status', $user->organizer) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit"
                                                    class="inline-flex items-center text-sm text-red-600 hover:text-red-900 transition-colors"
                                                    title="Отменить верификацию">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    Отозвать
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Пользователи не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
