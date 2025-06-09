@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('news.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
                <i class="fas fa-arrow-left mr-2"></i> Назад к новостям
            </a>

            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                @if ($news->image)
                    <img src="{{ asset('images/' . ($news->image ?? '1.jpg')) }}" alt="{{ $news->title }}"
                        class="w-full h-64 md:h-96 object-cover">
                @endif

                <div class="p-6 md:p-8">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500">
                            {{ $news->created_at->translatedFormat('d F Y') }}
                        </span>
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                            {{ $news->category }}
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $news->title }}</h1>

                    <div class="prose max-w-none text-gray-700 mb-6">
                        {!! $news->content !!}
                    </div>

                    <div class="flex items-center justify-between border-t pt-6">
                        <div class="flex items-center">
                            <div class="text-sm text-gray-500">
                                <span>Автор: {{ $news->author->name ?? 'Администрация' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </article>

            @if ($relatedNews->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Похожие новости</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach ($relatedNews as $related)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                              <img src="{{ asset('images/' . ($related->image ?? '1.jpg')) }}" alt="{{ $news->title }}"
                                    class="w-full h-40 object-cover">

                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 mb-2">{{ $related->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $related->excerpt }}</p>
                                    <a href="{{ route('news.show', $related) }}"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 text-sm font-medium">
                                        Читать далее
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
