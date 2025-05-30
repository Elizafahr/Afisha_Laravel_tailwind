@extends('admin.layout')

@section('title', 'Создание организатора')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Создание нового организатора</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад
            </a>
        </div>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.organizers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="user_id">Пользователь *</label>
                <select class="form-control @error('user_id') is-invalid @enderror"
                        id="user_id" name="user_id" required>
                    <option value="">Выберите пользователя</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $selectedUserId) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="organization_name">Название организации *</label>
                <input type="text" class="form-control @error('organization_name') is-invalid @enderror"
                       id="organization_name" name="organization_name"
                       value="{{ old('organization_name') }}" required>
                @error('organization_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="contact_person">Контактное лицо *</label>
                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                       id="contact_person" name="contact_person"
                       value="{{ old('contact_person') }}" required>
                @error('contact_person')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="contact_info">Контактная информация *</label>
                <input type="text" class="form-control @error('contact_info') is-invalid @enderror"
                       id="contact_info" name="contact_info"
                       value="{{ old('contact_info') }}" required>
                @error('contact_info')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="logo">Логотип</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('logo') is-invalid @enderror"
                           id="logo" name="logo">
                    <label class="custom-file-label" for="logo">Выберите файл</label>
                </div>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Форматы: JPEG, PNG. Максимальный размер: 2MB
                </small>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Создать организатора
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Показываем имя выбранного файла
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("logo").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
