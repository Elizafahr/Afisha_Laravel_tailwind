@extends('organizer.layout')

@section('title', 'Редактирование мероприятия')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Редактирование мероприятия</h2>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('organizer.events.update', $event) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Название мероприятия</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title', $event->title) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Категория</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="concert" @selected(old('category', $event->category) == 'concert')>Концерт</option>
                                        <option value="festival" @selected(old('category', $event->category) == 'festival')>Фестиваль</option>
                                        <option value="exhibition" @selected(old('category', $event->category) == 'exhibition')>Выставка</option>
                                        <option value="theater" @selected(old('category', $event->category) == 'theater')>Театр</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="start_datetime" class="form-label">Дата и время начала</label>
                                    <input type="datetime-local" class="form-control" id="start_datetime"
                                        name="start_datetime"
                                        value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_datetime" class="form-label">Дата и время окончания</label>
                                    <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime"
                                        value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Место проведения</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="{{ old('location', $event->location) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="age_restriction" class="form-label">Возрастное ограничение</label>
                                    <input type="number" class="form-control" id="age_restriction" name="age_restriction"
                                        value="{{ old('age_restriction', $event->age_restriction) }}" min="0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_poster" id="remove_poster"
                                            class="form-check-input">
                                        <label for="remove_poster" class="form-check-label">Удалить постер</label>
                                    </div>
                                    <label for="poster_url" class="form-label">Ссылка на постер</label>
                                    <input type="url" class="form-control" id="poster_url" name="poster_url"
                                        value=" ">
                                    <small class="text-muted">Или загрузите файл</small>
                                    <input type="file" class="form-control mt-2" id="poster_file" name="poster_file">

                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4 pt-2">
                                        <input type="hidden" name="is_free" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_free" name="is_free"
                                            value="1" @checked(old('is_free', $event->is_free) == 1)>
                                        <label class="form-check-label" for="is_free">Бесплатное мероприятие</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3" id="price-field"
                                style="{{ old('is_free', $event->is_free) ? 'display: none;' : '' }}">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Цена билета (₽)</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="{{ old('price', $event->price) }}" min="0" step="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="booking_type" class="form-label">Тип бронирования</label>
                                    <select class="form-select" id="booking_type" name="booking_type" required>
                                        <option value="seated" @selected(old('booking_type', $event->booking_type) == 'seated')>Места с рассадкой</option>
                                        <option value="general" @selected(old('booking_type', $event->booking_type) == 'general')>Общий вход</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3" id="seating-options"
                                style="{{ old('booking_type', $event->booking_type) == 'seated' ? '' : 'display: none;' }}">
                                <div class="col-md-6">
                                    <label for="rows" class="form-label">Количество рядов</label>
                                    <input type="number" class="form-control" id="rows" name="rows"
                                        value="{{ old('rows', $event->rows) }}" min="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="columns" class="form-label">Количество мест в ряду</label>
                                    <input type="number" class="form-control" id="columns" name="columns"
                                        value="{{ old('columns', $event->columns) }}" min="1">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="link" class="form-label">Ссылка на мероприятие</label>
                                    <input type="url" class="form-control" id="link" name="link"
                                        value="{{ old('link', $event->link) }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4 pt-2">
                                        <input class="form-check-input" type="checkbox" id="is_booking"
                                            name="is_booking" value="1" @checked(old('is_booking', $event->is_booking) == 1)>
                                        <label class="form-check-label" for="is_booking">Разрешить бронирование</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                    value="1" @checked(old('is_published', $event->is_published) == 1)>
                                <label class="form-check-label" for="is_published">Опубликовать мероприятие</label>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Сохранить изменения
                                </button>

                                <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Назад к списку
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('is_free').addEventListener('change', function() {
            const priceField = document.getElementById('price-field');
            if (this.checked) {
                priceField.style.display = 'none';
                document.getElementById('price').value = 0;
            } else {
                priceField.style.display = 'block';
            }
        });

        document.getElementById('booking_type').addEventListener('change', function() {
            toggleSeatingOptions();
        });

        function toggleSeatingOptions() {
            const seatingOptions = document.getElementById('seating-options');
            const bookingType = document.getElementById('booking_type').value;

            if (bookingType === 'seated') {
                seatingOptions.style.display = 'flex';
            } else {
                seatingOptions.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleSeatingOptions();
        });
    </script>
@endsection
