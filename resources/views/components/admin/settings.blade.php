@extends('layouts.admin')

@section('title', 'Настройки системы')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Настройки системы</h1>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-3xl">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700">Название сайта</label>
                    <input type="text" name="site_name" id="site_name" value="{{ old('site_name', config('app.name')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700">Часовой пояс</label>
                    <select name="timezone" id="timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ config('app.timezone') == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tickets_per_page" class="block text-sm font-medium text-gray-700">Билетов на странице</label>
                    <input type="number" name="tickets_per_page" id="tickets_per_page" min="1" max="100" value="{{ old('tickets_per_page', config('app.tickets_per_page', 15)) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Сохранить настройки
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
