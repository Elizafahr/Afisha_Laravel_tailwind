@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Новости и анонсы</h1>

            <div class="relative">
                <select
                    id="news-filter"
                    class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-8 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    <option value="all">Все новости</option>
                    <option value="announcements">Анонсы</option>
                    <option value="reports">Отчеты</option>
                    <option value="interviews">Интервью</option>
                </select>
            </div>
        </div>

        {{-- @if($pinnedNews->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Важные новости</h2>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($pinnedNews as $news)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="relative">
                                <img
                                    src="{{ asset('images/' . ($news->image ?? 'default-news.jpg')) }}"
                                    alt="{{ $news->title }}"
                                    class="w-full h-48 object-cover"
                                >
                                <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    <i class="fas fa-thumbtack mr-1"></i> Важно
                                </span>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">
                                        {{ $news->created_at->format('d.m.Y') }}
                                    </span>
                                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                        {{ $news->category }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $news->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $news->excerpt }}</p>
                                <a
                                    href="{{ route('news.show', $news) }}"
                                    class="inline-flex items-center text-red-600 hover:text-red-800 font-medium"
                                >
                                    Читать далее
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif --}}

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            @foreach($latestNews as $news)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img
                        src="{{ asset('images/' . ($news->image ?? 'default-news.jpg')) }}"
                        alt="{{ $news->title }}"
                        class="w-full h-48 object-cover"
                    >
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">
                                {{ $news->created_at->format('d.m.Y') }}
                            </span>
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                {{ $news->category }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $news->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $news->excerpt }}</p>
                        <a
                            href="{{ route('news.show', $news) }}"
                            class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm"
                        >
                            Читать далее
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($latestNews->hasPages())
            <div class="flex justify-center mt-8">
                {{ $latestNews->links() }}
            </div>
        @endif

        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Категории новостей</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a
                    href="{{ route('news.index', ['category' => 'announcements']) }}"
                    class="bg-white p-6 rounded-lg shadow-sm text-center hover:bg-red-50 transition"
                >
                    <div class="text-red-600 mb-2">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <span class="font-medium">Анонсы</span>
                    <div class="text-xs text-gray-500 mt-1">{{ $categoriesCount['announcements'] ?? 0 }} новостей</div>
                </a>

                <a
                    href="{{ route('news.index', ['category' => 'reports']) }}"
                    class="bg-white p-6 rounded-lg shadow-sm text-center hover:bg-red-50 transition"
                >
                    <div class="text-red-600 mb-2">
                        <i class="fas fa-newspaper text-2xl"></i>
                    </div>
                    <span class="font-medium">Отчеты</span>
                    <div class="text-xs text-gray-500 mt-1">{{ $categoriesCount['reports'] ?? 0 }} новостей</div>
                </a>

                <a
                    href="{{ route('news.index', ['category' => 'interviews']) }}"
                    class="bg-white p-6 rounded-lg shadow-sm text-center hover:bg-red-50 transition"
                >
                    <div class="text-red-600 mb-2">
                        <i class="fas fa-microphone text-2xl"></i>
                    </div>
                    <span class="font-medium">Интервью</span>
                    <div class="text-xs text-gray-500 mt-1">{{ $categoriesCount['interviews'] ?? 0 }} новостей</div>
                </a>

                <a
                    href="{{ route('news.index', ['category' => 'reviews']) }}"
                    class="bg-white p-6 rounded-lg shadow-sm text-center hover:bg-red-50 transition"
                >
                    <div class="text-red-600 mb-2">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <span class="font-medium">Обзоры</span>
                    <div class="text-xs text-gray-500 mt-1">{{ $categoriesCount['reviews'] ?? 0 }} новостей</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
