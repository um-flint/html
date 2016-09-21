<?php

namespace UMFlint\Html;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
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
            $session = $app->make(SessionManager::class);

            $form = new Form($config);
            $form->old($request->old());
            if ($session->has('errors')) {
                $form->setErrors($session->get('errors'));
            }

            return $form;
        });

        $this->app->alias('umflint.html.form', Form::class);
    }
}