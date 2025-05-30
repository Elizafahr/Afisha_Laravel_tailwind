@extends('layouts.admin')

@section('title', 'Редактирование мероприятия')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Редактирование мероприятия</h1>

    <form action="{{ route('admin.events.update', $event) }}" method="POST" class="max-w-3xl">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Поля формы аналогичны create.blade.php, но с заполненными значениями -->
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Название</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Остальные поля с подставленными значениями $event -->

            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Обновить
                </button>
                <a href="{{ route('admin.events.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                    Отмена
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
