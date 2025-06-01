@extends('layouts.admin')

@section('title', 'Мероприятия')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Мероприятия</h1>
        <a href="{{ route('admin.events.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Создать мероприятие
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase min-w-[200px]">Место</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($events as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $event->start_datetime->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 max-w-[300px]">
                            <div class="truncate hover:text-clip hover:whitespace-normal" title="{{ $event->location }}">
                                {{ $event->location }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('admin.events.edit', $event) }}" class="text-blue-500 hover:text-blue-700">
                                Редактировать
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Удалить мероприятие?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection
