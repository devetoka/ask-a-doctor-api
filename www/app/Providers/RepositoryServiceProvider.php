<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        'App\Repositories\Category\CategoryRepositoryInterface' => 'App\Repositories\Category\CategoryRepository',
        'App\Repositories\User\UserRepositoryInterface' => 'App\Repositories\User\UserRepository',
        'App\Repositories\Question\QuestionRepositoryInterface' => 'App\Repositories\Question\QuestionRepository'
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $repository){
            $this->app->bind($interface, $repository);
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //

    }
}
