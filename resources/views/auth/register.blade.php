<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('content')
<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gray-100 px-6 py-4">
                <h2 class="text-center text-2xl font-bold text-gray-800">Регистрация</h2>
            </div>
            <div class="px-8 py-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <!-- Основные поля пользователя -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Имя пользователя</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus
                            class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Электронная почта</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="mt-1 block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                            class="mt-1 block w-full px-3 py-2 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700">Подтвердите пароль</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Чекбокс "Я организатор" -->
                    <div class="flex items-center">
                        <input id="is_organizer" name="is_organizer" type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            {{ old('is_organizer') ? 'checked' : '' }}>
                        <label for="is_organizer" class="ml-2 block text-sm text-gray-700">
                            Я организатор мероприятий
                        </label>
                    </div>

                    <!-- Дополнительные поля для организатора (появляются при выборе чекбокса) -->
                    <div id="organizer-fields" style="{{ old('is_organizer') ? '' : 'display: none;' }}">
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Информация об организации</h3>

                            <div class="form-group">
                                <label for="organization_name">Название организации *</label>
                                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    id="organization_name" name="organization_name"
                                    value="{{ old('organization_name') }}">
                                @error('organization_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label for="contact_person">Контактное лицо *</label>
                                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    id="contact_person" name="contact_person"
                                    value="{{ old('contact_person') }}">
                                @error('contact_person')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label for="contact_info">Контактная информация *</label>
                                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    id="contact_info" name="contact_info"
                                    value="{{ old('contact_info') }}">
                                @error('contact_info')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label for="description">Описание организации</label>
                                <textarea class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label for="logo">Логотип организации</label>
                                <div class="mt-1">
                                    <input type="file" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100"
                                        id="logo" name="logo">
                                </div>
                                @error('logo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <small class="form-text text-muted">
                                    Форматы: JPEG, PNG. Максимальный размер: 2MB
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            Я согласен с <a href="#" class="text-indigo-600 hover:text-indigo-500">условиями использования</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Зарегистрироваться
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Показываем/скрываем поля организатора при изменении чекбокса
    document.getElementById('is_organizer').addEventListener('change', function(e) {
        const organizerFields = document.getElementById('organizer-fields');
        organizerFields.style.display = e.target.checked ? 'block' : 'none';

        // Делаем поля обязательными, если организатор
        const requiredFields = organizerFields.querySelectorAll('[name]');
        requiredFields.forEach(field => {
            if (field.name === 'organization_name' ||
                field.name === 'contact_person' ||
                field.name === 'contact_info') {
                field.required = e.target.checked;
            }
        });
    });
</script>
@endsection
