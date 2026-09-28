<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleAnalytic;
use App\Services\SEOService;
use Illuminate\Http\Request;

class ArticleAnalyticsController extends Controller
{
    protected $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function index()
    {
        $topArticles = Article::published()
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'slug', 'views_count', 'published_at']);

        $recentViews = ArticleAnalytic::latest()
            ->limit(30)
            ->get();

        $totalViews = ArticleAnalytic::sum('views');
        $totalUniqueVisitors = ArticleAnalytic::sum('unique_visitors');
        $averageScrollDepth = ArticleAnalytic::avg('scroll_depth') ?? 0;

        return view('admin.analytics.index', compact(
            'topArticles',
            'recentViews',
            'totalViews',
            'totalUniqueVisitors',
            'averageScrollDepth'
        ));
    }

    public function show(Article $article)
    {
        $article->load('meta', 'keywords', 'faqs', 'analytics', 'user', 'category');

        $analytics = $article->analytics()
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $seoScore = $this->seoService->getSEOScoreBreakdown($article);
        $seoRecommendations = $this->seoService->generateRecommendations($article);

        $chartData = [
            'dates' => $analytics->pluck('date')->map(fn($d) => $d->format('d M'))->toArray(),
            'views' => $analytics->pluck('views')->toArray(),
            'visitors' => $analytics->pluck('unique_visitors')->toArray(),
        ];

        return view('admin.analytics.show', compact(
            'article',
            'analytics',
            'seoScore',
            'seoRecommendations',
            'chartData'
        ));
    }

    public function recordView(Article $article, Request $request)
    {
        ArticleAnalytic::updateOrCreate(
            [
                'article_id' => $article->id,
                'date' => now()->toDateString(),
            ],
            [
                'views' => \DB::raw('views + 1'),
                'scroll_depth' => $request->input('scroll_depth', 0),
                'avg_time_on_page' => $request->input('time_on_page', 0),
            ]
        );

        $article->increment('views_count');

        return response()->json(['success' => true]);
    }
}
