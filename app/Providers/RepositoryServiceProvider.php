<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(ArticleRepository::class, function ($app) {
            return new ArticleRepository($app->make('App\Models\Article'));
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository($app->make('App\Models\Category'));
        });

        $this->app->bind(TagRepository::class, function ($app) {
            return new TagRepository($app->make('App\Models\Tag'));
        });

        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make('App\Models\User'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
