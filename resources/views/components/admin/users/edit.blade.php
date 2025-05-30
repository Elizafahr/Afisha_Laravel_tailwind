@extends('layouts.admin')

@section('title', 'Редактирование пользователя')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Редактирование пользователя</h1>

<form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->username) }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Роль пользователя</label>
                        <select name="role" id="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Обычный пользователь
                            </option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Администратор</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Сохранить
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                        Отмена
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
