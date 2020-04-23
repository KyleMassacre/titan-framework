<?php
namespace PbbgIo\Titan\Providers;

use PbbgIo\Titan\Http\Validators\MinIfNotEmpty;
use PbbgIo\Titan\Http\Validators\MaxIfNotEmpty;
use PbbgIo\Titan\Support\BanUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use PbbgIo\Titan\Http\Middleware\PlayableNotBannedMiddleware;

class BanUserServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->validatorExtensions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(BanUser::class, function() {
            return new BanUser();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../.../config/ban.php' => config_path('banuser.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../../config/ban.php', 'banuser'
        );
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [BanUser::class];
    }

    private function validatorExtensions()
    {
        Validator::extend('min_if_not_empty', MinIfNotEmpty::class. '@validate');
        Validator::replacer('min_if_not_empty', function ($message, $attribute, $rule, $parameters) {

            $validatorMessage = "";
            if(count($parameters) == 1)
            {
                $validatorMessage .= "greater than {$parameters[0]} characters";
            } else {
                $min = min($parameters);
                $max = max($parameters);

                $validatorMessage .= "in between {$min} and {$max} characters";
            }

            return str_replace([':message'], $validatorMessage, $message);
        });

        Validator::extend('max_if_not_empty', MaxIfNotEmpty::class. '@validate');
        Validator::replacer('max_if_not_empty', function ($message, $attribute, $rule, $parameters) {

            $validatorMessage = "";
            if(count($parameters) == 1)
            {
                $validatorMessage .= "less than {$parameters[0]} characters";
            } else {
                $min = min($parameters);
                $max = max($parameters);

                $validatorMessage .= "in between {$min} and {$max} characters";
            }

            return str_replace([':message'], $validatorMessage, $message);
        });
    }
}
