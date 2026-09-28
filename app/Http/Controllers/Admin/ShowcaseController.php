<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Subscriber;
use Illuminate\Support\Carbon;

class ShowcaseController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized access to showcase dashboard');
        }

        // Get current month and last month dates
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Calculate metrics
        $metrics = [
            'articles' => [
                'total' => Article::where('status', 'published')->count(),
                'this_month' => Article::where('status', 'published')
                    ->whereDate('published_at', '>=', $thisMonth)
                    ->count(),
                'last_month' => Article::where('status', 'published')
                    ->whereBetween('published_at', [$lastMonth, $lastMonthEnd])
                    ->count(),
            ],
            'users' => [
                'total' => User::count(),
                'this_month' => User::whereDate('created_at', '>=', $thisMonth)->count(),
                'last_month' => User::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            ],
            'categories' => [
                'total' => Category::whereNull('parent_id')->count(),
                'this_month' => Category::whereNull('parent_id')
                    ->whereDate('created_at', '>=', $thisMonth)
                    ->count(),
            ],
            'subscribers' => [
                'total' => Subscriber::where('is_active', true)->count(),
                'this_month' => Subscriber::where('is_active', true)
                    ->whereDate('created_at', '>=', $thisMonth)
                    ->count(),
            ],
            'homepage_sections' => HomepageSection::where('status', true)->count(),
            'avg_engagement' => (int) Article::where('status', 'published')->avg('views_count') ?? 0,
        ];

        return view('admin.showcase.index', compact('metrics'));
    }
}
