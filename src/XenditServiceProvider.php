<?php

namespace Mrfansi\LaravelXendit;

use Mrfansi\LaravelXendit\Commands\InvoiceCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class XenditServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-xendit')
            ->hasConfigFile('xendit')
            ->hasViews()
            ->hasMigration('create_xendit_table')
            ->hasCommand(InvoiceCommand::class);
    }
}
