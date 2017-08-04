<?php

namespace UMFlint\Html;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use UMFlint\Html\Form\Form;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Boot.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/html.php' => config_path('html.php'),
        ]);
    }

    /**
     * Register
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/html.php', 'html');

        $this->app->singleton('umflint.html.form', function ($app) {
            $request = $app->make(Request::class);

            $form = new Form();
            $form->old($request->old());

            return $form;
        });

        $this->app->alias('umflint.html.form', Form::class);
    }
}