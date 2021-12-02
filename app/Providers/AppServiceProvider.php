<?php

namespace App\Providers;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Mediators\Mediator;
use App\Repositories\CommentRepository;
use App\Repositories\IssueRepository;
use App\Repositories\UserRepository;
use App\Services\CommentService;
use App\Services\IssueService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when([IssueController::class])
            ->needs(Mediator::class)
            ->give(function () {
                return new Mediator(
                    $this->app->make(IssueRepository::class),
                    $this->app->make(IssueService::class)
                );
            });
        $this->app->when([CommentController::class])
            ->needs(Mediator::class)
            ->give(function () {
                return new Mediator(
                    $this->app->make(CommentRepository::class),
                    $this->app->make(CommentService::class)
                );
            });
        $this->app->when([AdminController::class])
            ->needs(Mediator::class)
            ->give(function () {
                return new Mediator(
                    $this->app->make(UserRepository::class),
                    $this->app->make(UserService::class)
                );
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
