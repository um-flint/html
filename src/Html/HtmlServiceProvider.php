<?php

namespace UMFlint\Html;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use UMFlint\Html\Form\Form;

class HtmlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('umflint.html.form', function ($app) {
            $request = $app->make(Request::class);

            $form = new Form();
            $form->old($request->old());

            return $form;
        });

        $this->app->alias('umflint.html.form', Form::class);
    }
}