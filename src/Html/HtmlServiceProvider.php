<?php

namespace UMFlint\Html;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use UMFlint\Html\Form\Form;

class HtmlServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/html.php' => config_path('html.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/html.php', 'html');

        $this->app->singleton('umflint.html.form', function ($app) {
            $config = $app['config']['html'];
            $request = $app->make(Request::class);

            return new Form($config, $request->old());
        });

        $this->app->alias('umflint.html.form', Form::class);
    }
}