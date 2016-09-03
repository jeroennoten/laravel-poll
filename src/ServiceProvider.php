<?php

namespace JeroenNoten\LaravelPoll;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\ServiceProvider as AdminLteServiceProvider;
use JeroenNoten\LaravelPackageHelper\ServiceProviderTraits;
use JeroenNoten\LaravelPoll\Http\Controllers\Admin\Polls;
use JeroenNoten\LaravelPoll\Http\Controllers\Votes;
use JeroenNoten\LaravelPoll\Http\ViewComposers\Poll;

class ServiceProvider extends BaseServiceProvider
{
    use ServiceProviderTraits;

    public function register()
    {
        $this->app->register(AdminLteServiceProvider::class);
    }

    public function boot(Router $router, Dispatcher $dispatcher, Factory $view)
    {
        $this->addMenuItem($dispatcher);
        $this->defineRoutes($router);
        $this->loadViews();
        $this->publishMigrations();
        $this->composeViews($view);
    }

    private function addMenuItem(Dispatcher $dispatcher)
    {
        $dispatcher->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text' => 'Polls',
                'url'  => 'admin/polls',
            ]);
        });

    }

    private function defineRoutes(Router $router)
    {
        $router->group(['middleware' => 'web'], function (Router $router) {

            $router->group([
                'prefix' => 'polls/answers/{answer}',
                'as' => 'polls.answers.',
            ], function (Router $router) {
                $router->resource('votes', Votes::class, ['only' => 'store']);
            });

            $router->group([
                'prefix'     => 'admin',
                'as'         => 'admin.',
                'middleware' => 'auth',
            ], function (Router $router) {
                $router->post('polls/disable', Polls::class.'@disable')->name('polls.disable');
                $router->post('polls/enable', Polls::class.'@enable')->name('polls.enable');
                $router->resource('polls', Polls::class, ['except' => ['show']]);
            });


        });
    }

    protected function name()
    {
        return 'poll';
    }

    protected function path()
    {
        return __DIR__.'/..';
    }

    protected function getContainer()
    {
        return $this->app;
    }

    private function composeViews(Factory $view)
    {
        $view->composer('poll::poll', Poll::class);
    }
}
