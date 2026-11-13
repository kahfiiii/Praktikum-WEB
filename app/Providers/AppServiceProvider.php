<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Material;
use App\Models\Quiz;
use App\Policies\MaterialPolicy;
use App\Policies\QuizPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
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
        Relation::morphMap([
            'material' => Material::class,
            'comment' => Comment::class,
        ]);

        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(Quiz::class, QuizPolicy::class);
    }
}
