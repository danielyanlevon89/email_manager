<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('ProtoneMedia\Splade\Http\TableBulkActionController', 'App\Services\SpladeTableBulkActionService');

        $isLocal = $this->app->environment('local') || $this->app->environment('production');
        if ($isLocal && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Arr::macro('getEmailAddressesFromString', function ($value) {
            $emails = [];
            foreach(preg_split('/\s/', $value) as $token) {
                $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
                if ($email !== false) {
                    $emails[] = $email;
                }
            }
            return $emails;
        });

        Str::macro('getEmailNameFromString', function ($value) {
            $email_name = '';
            foreach(preg_split('/\s/', $value) as $token) {
                $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
                if ($email !== false) {
                    $email_name = str_replace([$email,'<','>'],'',$value);

                }
            }
            return $email_name;
        });

        Str::macro('getEmailAddressesFromString', function ($value) {
            $emails = [];
            foreach(preg_split('/\s/', $value) as $token) {
                $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
                if ($email !== false) {
                    $emails[] = $email;
                }
            }
            return implode(',',$emails);
        });

        Str::macro('removeDublicatesFromString', function ($value) {

            return Str::of($value)->explode(',')->unique()->implode(',');

        });
        Validator::extend("emails", function($attribute, $value, $parameters) {
            $emails = explode(",", $value);
            $rules = [
                'email' => 'required|email',
            ];
            foreach ($emails as $email) {
                $data = [
                    'email' => $email
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });

    }
}
