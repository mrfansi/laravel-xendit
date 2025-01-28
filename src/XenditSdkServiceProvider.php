<?php

namespace Mrfansi\XenditSdk;

use Mrfansi\XenditSdk\Commands\XenditSdkCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class XenditSdkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('xendit-sdk')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_xendit_sdk_table')
            ->hasCommand(XenditSdkCommand::class);
    }
}
