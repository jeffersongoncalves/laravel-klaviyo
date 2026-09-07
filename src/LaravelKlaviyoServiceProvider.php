<?php

namespace Jeffersongoncalves\LaravelKlaviyo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelKlaviyoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-klaviyo')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('laravel-klaviyo', fn () => new LaravelKlaviyo);
    }
}
