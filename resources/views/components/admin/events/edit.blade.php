@extends('layouts.admin')

@section('title', 'Редактирование мероприятия')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Редактирование мероприятия</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Название мероприятия</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Категория</label>
                        <select name="category" id="category" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="concert" {{ old('category', $event->category) == 'concert' ? 'selected' : '' }}>Концерт</option>
                            <option value="festival" {{ old('category', $event->category) == 'festival' ? 'selected' : '' }}>Фестиваль</option>
                            <option value="exhibition" {{ old('category', $event->category) == 'exhibition' ? 'selected' : '' }}>Выставка</option>
                            <option value="theater" {{ old('category', $event->category) == 'theater' ? 'selected' : '' }}>Театр</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="organizer_id" class="block text-sm font-medium text-gray-700">Организатор</label>
                        <select name="organizer_id" id="organizer_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($organizers as $organizer)
                                <option value="{{ $organizer->organizer_id }}" {{ old('organizer_id', $event->organizer_id) == $organizer->organizer_id ? 'selected' : '' }}>
                                    {{ $organizer->organization_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="age_restriction" class="block text-sm font-medium text-gray-700">Возрастное ограничение</label>
                        <input type="number" name="age_restriction" id="age_restriction" min="0"
                            value="{{ old('age_restriction', $event->age_restriction) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea name="description" id="description" rows="4" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_datetime" class="block text-sm font-medium text-gray-700">Дата и время начала</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" required
                            value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="end_datetime" class="block text-sm font-medium text-gray-700">Дата и время окончания</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" required
                            value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Место проведения</label>
                        <input type="text" name="location" id="location" required
                            value="{{ old('location', $event->location) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <div class="form-check">
    <input type="checkbox" name="remove_poster" id="remove_poster" class="form-check-input">
    <label for="remove_poster" class="form-check-label">Удалить постер</label>
</div>
                        <label for="poster_url" class="block text-sm font-medium text-gray-700">Ссылка на постер</label>
                        <input type="url" name="poster_url" id="poster_url"
                            value=""
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Или загрузите файл</p>
                        <input type="file" name="poster_file" id="poster_file"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @if($event->poster_url)
                            <div class="mt-2">
                                <img src="{{ $event->poster_url }}" alt="Постер мероприятия" class="h-20">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center">
                            <input type="hidden" name="is_free" value="0">
                            <input type="checkbox" name="is_free" id="is_free" value="1"
                                {{ old('is_free', $event->is_free) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <label for="is_free" class="ml-2 block text-sm text-gray-700">Бесплатное мероприятие</label>
                        </div>
                    </div>

                    <div>
                        <label for="booking_type" class="block text-sm font-medium text-gray-700">Тип бронирования</label>
                        <select name="booking_type" id="booking_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="seated" {{ old('booking_type', $event->booking_type) == 'seated' ? 'selected' : '' }}>Места с рассадкой</option>
                            <option value="general" {{ old('booking_type', $event->booking_type) == 'general' ? 'selected' : '' }}>Общий вход</option>
                        </select>
                    </div>
                </div>

                <div id="price-field" style="{{ old('is_free', $event->is_free) ? 'display: none;' : '' }}">
                    <label for="price" class="block text-sm font-medium text-gray-700">Цена билета (₽)</label>
                    <input type="number" name="price" id="price" min="0" step="1"
                        value="{{ old('price', $event->price) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div id="seating-options" style="{{ old('booking_type', $event->booking_type) == 'seated' ? '' : 'display: none;' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rows" class="block text-sm font-medium text-gray-700">Количество рядов</label>
                            <input type="number" name="rows" id="rows" min="1"
                                value="{{ old('rows', $event->rows ?? 10) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="columns" class="block text-sm font-medium text-gray-700">Количество мест в ряду</label>
                            <input type="number" name="columns" id="columns" min="1"
                                value="{{ old('columns', $event->columns ?? 20) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="link" class="block text-sm font-medium text-gray-700">Ссылка на мероприятие</label>
                        <input type="url" name="link" id="link"
                            value="{{ old('link', $event->link) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <div class="flex items-center">
                            <input type="hidden" name="is_booking" value="0">
                            <input type="checkbox" name="is_booking" id="is_booking" value="1"
                                {{ old('is_booking', $event->is_booking) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <label for="is_booking" class="ml-2 block text-sm text-gray-700">Разрешить бронирование</label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                        {{ old('is_published', $event->is_published) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <label for="is_published" class="ml-2 block text-sm text-gray-700">Опубликовать мероприятие</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Отмена
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обработчик для бесплатного мероприятия
        const isFreeCheckbox = document.getElementById('is_free');
        if (isFreeCheckbox) {
            isFreeCheckbox.addEventListener('change', function() {
                const priceField = document.getElementById('price-field');
                if (this.checked) {
                    priceField.style.display = 'none';
                    document.getElementById('price').value = 0;
                } else {
                    priceField.style.display = 'block';
                }
            });
        }

        // Обработчик для типа бронирования
        const bookingTypeSelect = document.getElementById('booking_type');
        if (bookingTypeSelect) {
            bookingTypeSelect.addEventListener('change', toggleSeatingOptions);
        }

        // Функция для показа/скрытия полей рассадки
        function toggleSeatingOptions() {
            const seatingOptions = document.getElementById('seating-options');
            if (seatingOptions) {
                const bookingType = document.getElementById('booking_type').value;
                seatingOptions.style.display = bookingType === 'seated' ? 'block' : 'none';
            }
        }

        // Инициализация при загрузке
        toggleSeatingOptions();

        // Принудительно триггерим событие change для is_free, чтобы установить начальное состояние
        if (isFreeCheckbox) {
            isFreeCheckbox.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection
