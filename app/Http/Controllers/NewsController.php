<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
{
    $query = News::query()->orderByDesc('is_pinned')->orderByDesc('created_at');

    if ($request->has('category') && in_array($request->category, ['announcements', 'reports', 'interviews', 'reviews'])) {
        $query->where('category', $request->category);
    }

    $pinnedNews = News::where('is_pinned', true)
        ->orderByDesc('created_at')
        ->limit(2)
        ->get();

    $latestNews = $query->paginate(9);

    $categoriesCount = [
        'announcements' => News::where('category', 'announcements')->count(),
        'reports' => News::where('category', 'reports')->count(),
        'interviews' => News::where('category', 'interviews')->count(),
        'reviews' => News::where('category', 'reviews')->count(),
    ];

    return view('news.index', compact('pinnedNews', 'latestNews', 'categoriesCount'));
}

   public function show(News $news)
{
    $relatedNews = News::where('category', $news->category)
        ->where('news_id', '!=', $news->id)
        ->latest()
        ->take(2)
        ->get();

    return view('news.show', [
        'news' => $news,
        'relatedNews' => $relatedNews
    ]);
}
}
