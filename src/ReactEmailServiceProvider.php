<?php

namespace Spectate\ReactEmail;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spectate\ReactEmail\Commands\BuildReactEmailsCommand;
use Spectate\ReactEmail\Commands\MakeReactEmailCommand;
use Spectate\ReactEmail\Commands\ReactEmailDevServerCommand;

class ReactEmailServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-react-email')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                MakeReactEmailCommand::class,
                BuildReactEmailsCommand::class,
                ReactEmailDevServerCommand::class,
            ]);
    }
}
