<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');
            $view->with('settings', $settings);

            // Template Data
            $view->with('appname', config('app.name'));

            // Mocking Nav Data for now (can be dynamic later)
            $navs = [
                (object) ['text' => 'Home', 'url' => route('home')],
                (object) ['text' => 'Articles', 'url' => route('articles.index')],
                (object) ['text' => 'Categories', 'url' => route('categories.index')],
            ];
            $view->with('navs', $navs);

            $categories = \App\Models\Category::whereNull('parent_id')->with('children')->get();
            $view->with('categories', $categories);

            $navsGroup = $categories->map(function ($cat) {
                return (object) [
                    'text' => $cat->name,
                    'url' => route('categories.show', $cat),
                    'children' => $cat->children->map(function ($child) {
                        return (object) ['text' => $child->name, 'url' => route('categories.show', $child)];
                    })
                ];
            });
            $view->with('navsGroup', $navsGroup);
        });
    }
}
