@extends('organizer.layout')

@section('title', 'Редактирование мероприятия')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Редактирование мероприятия</h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('organizer.events.update', $event) }}" method="POST" enctype="multipart/form-data">
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

                        <div class="mb-3">
                            <label for="title" class="form-label">Название мероприятия</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title', $event->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control" id="description" name="description"
                                      rows="5" required>{{ old('description', $event->description) }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_datetime" class="form-label">Дата и время начала</label>
                                <input type="datetime-local" class="form-control" id="start_datetime"
                                       name="start_datetime" value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_datetime" class="form-label">Дата и время окончания</label>
                                <input type="datetime-local" class="form-control" id="end_datetime"
                                       name="end_datetime" value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Место проведения</label>
                            <input type="text" class="form-control" id="location" name="location"
                                   value="{{ old('location', $event->location) }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Цена (руб.)</label>
                                <input type="number" class="form-control" id="price" name="price"
                                       min="0" step="0.01" value="{{ old('price', $event->price) }}" required>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="capacity" class="form-label">Количество мест</label>
                                <input type="number" class="form-control" id="capacity" name="capacity"
                                       min="1" value="{{ old('capacity', $event->capacity) }}" required>
                            </div> --}}
                        </div>

                      <div class="mb-3">
    <label for="image" class="form-label">Изображение</label>
    <input type="file" class="form-control" id="image" name="image">
    @if($event->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="img-thumbnail" style="max-height: 200px;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                <label class="form-check-label" for="remove_image">
                    Удалить текущее изображение
                </label>
            </div>
        </div>
    @endif
</div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_published"
                                       name="is_published" value="1" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Опубликовать мероприятие
                                </label>
                            </div>
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
@endsection
