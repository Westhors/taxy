<?php

namespace App\Providers;

use App\Rules\EmailOrPhoneRule;
use App\Rules\PhoneRule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register "phone" rule
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return (new PhoneRule())->passes($attribute, $value);
        }, (new PhoneRule())->message());

        // Register "email_or_phone" rule
        Validator::extend('email_or_phone', function ($attribute, $value, $parameters, $validator) {
            return (new EmailOrPhoneRule())->passes($attribute, $value);
        }, (new EmailOrPhoneRule())->message());
    }
}