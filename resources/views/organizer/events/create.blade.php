@extends('organizer.layout')

@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ $title }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
            @csrf
            @if(isset($event)) @method('PUT') @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Название мероприятия</label>
                    <input type="text" class="form-control" id="title" name="title"
                           value="{{ old('title', $event->title ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Категория</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="concert" {{ (old('category', $event->category ?? '') == 'concert' ? 'selected' : '' }}>Концерт</option>
                        <option value="festival" {{ (old('category', $event->category ?? '') == 'festival' ? 'selected' : '' }}>Фестиваль</option>
                        <option value="exhibition" {{ (old('category', $event->category ?? '') == 'exhibition' ? 'selected' : '' }}>Выставка</option>
                        <option value="theater" {{ (old('category', $event->category ?? '') == 'theater' ? 'selected' : '' }}>Театр</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $event->description ?? '') }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="start_datetime" class="form-label">Дата и время начала</label>
                    <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime"
                           value="{{ old('start_datetime', isset($event) ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="end_datetime" class="form-label">Дата и время окончания</label>
                    <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime"
                           value="{{ old('end_datetime', isset($event) ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="location" class="form-label">Место проведения</label>
                    <input type="text" class="form-control" id="location" name="location"
                           value="{{ old('location', $event->location ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="age_restriction" class="form-label">Возрастное ограничение</label>
                    <input type="number" class="form-control" id="age_restriction" name="age_restriction"
                           value="{{ old('age_restriction', $event->age_restriction ?? '') }}" min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="poster_url" class="form-label">Ссылка на постер</label>
                    <input type="url" class="form-control" id="poster_url" name="poster_url"
                           value="{{ old('poster_url', $event->poster_url ?? '') }}">
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mt-4 pt-2">
                        <input class="form-check-input" type="checkbox" id="is_free" name="is_free"
                               {{ old('is_free', isset($event) ? $event->is_free : false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">Бесплатное мероприятие</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3" id="price-field" style="{{ old('is_free', isset($event) ? $event->is_free : false) ? 'display: none;' : '' }}">
                <div class="col-md-6">
                    <label for="price" class="form-label">Цена билета (₽)</label>
                    <input type="number" class="form-control" id="price" name="price"
                           value="{{ old('price', $event->price ?? 0) }}" min="0" step="1">
                </div>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                       {{ old('is_published', isset($event) ? $event->is_published : false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Опубликовать мероприятие</label>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>

@push('scripts')
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
</script>
@endpush
@endsection
