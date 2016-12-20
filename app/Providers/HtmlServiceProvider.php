<?php
namespace App\Providers;
use App\Libraries\BootstrapForm;
use App\Libraries\HtmlBuilder;

use Collective\Html\HtmlServiceProvider as IlluminateHtmlServiceProvider;
use Collective\Html\FormFacade as Form;

class HtmlServiceProvider extends IlluminateHtmlServiceProvider {
    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        parent::registerFormBuilder(); # Get main one first.

        # Load our own version of bootstrap_form, fixing some stupid stuff.

        $this->app->singleton('bootstrap_form', function($app) {
            return new BootstrapForm($app['html'], $app['form'], $app['config']);
        });

        # Now hack form builder a bit.... so we can guess the model and qualify a namespace
        Form::macro('getModel',function() {
            return $this->model;
        });

        // $this->app->singleton('form', function($app)
        // {
        //     $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken(), $app['config']);
        //     return $form->setSessionStore($app['session.store']);
        // });
    }

    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app)
        {
            return new HtmlBuilder($app['url'], $app['view']);
        });
    }
}