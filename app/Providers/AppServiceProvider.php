<?php

namespace App\Providers;

use App\Models\ChallengeApplication;
use App\Models\Comment;
use App\Models\IdeaKey;
use App\Models\Meet;
use App\Models\Package;
use App\Models\Position;
use App\Models\Post;
use App\Policies\ChallengeApplicationPolicy;
use App\Policies\CommentPolicy;
use App\Policies\IdeaKeyPolicy;
use App\Policies\MeetPolicy;
use App\Policies\PackagePolicy;
use App\Policies\PositionPolicy;
use App\Policies\PostPolicy;
use App\View\Components\Posts\Github;
use App\View\Components\Posts\Hidden;
use App\View\Components\Posts\LinkPreview;
use App\View\Components\Posts\Youtube;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });


        Paginator::useBootstrapFive();

        Blade::component('github', Github::class);
        Blade::component('youtube', Youtube::class);
        Blade::component('link', LinkPreview::class);
        Blade::component('hidden', Hidden::class);

        $this->registerPolicies();
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    protected function registerPolicies()
    {
        foreach ($this->policies() as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * The model to policy mappings for the application.
     *
     * @return string[]
     */
    protected function policies()
    {
        return [
            Comment::class              => CommentPolicy::class,
            Meet::class                 => MeetPolicy::class,
            Post::class                 => PostPolicy::class,
            Package::class              => PackagePolicy::class,
            Position::class             => PositionPolicy::class,
            IdeaKey::class              => IdeaKeyPolicy::class,
            ChallengeApplication::class => ChallengeApplicationPolicy::class,
        ];
    }
}
