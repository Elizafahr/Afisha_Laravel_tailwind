<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каменск-События - Агрегатор мероприятий Каменска-Уральского</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Добавьте в секцию head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .seating-grid {
        max-width: 800px;
        margin: 0 auto;
    }
    .stage {
        width: 80%;
        margin: 0 auto;
        padding: 8px;
        background: #f0f0f0;
        border-radius: 4px;
    }
    .seat {
        transition: all 0.2s ease;
    }
    .seat.selected {
        background-color: #3b82f6 !important;
        color: white;
    }
    .col-header {
        font-size: 0.75rem;
        color: #666;
    }
</style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Добавьте этот код где-нибудь в верхней части шаблона -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    @include('components.header')

    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    @include('components.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    @push('scripts')
    <script src="{{ asset('js/favorites.js') }}"></script>
@endpush
</body>
</html>
