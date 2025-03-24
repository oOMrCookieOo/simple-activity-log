<?php

namespace Mrcookie\SimpleActivityLog;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SimpleActivityLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('simple-activity-log')
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand
                    ->publishConfigFile()
                    ->startWith(function (InstallCommand $installCommand) {
                        $installCommand->call('vendor:publish', [
                            '--provider' => "Spatie\Activitylog\ActivitylogServiceProvider",
                            '--tag' => 'activitylog-migrations',
                        ]);
                    });
            });
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        if (config('simple-activity-log.enabled', true) && ! empty(config('simple-activity-log.registered'))) {
            foreach (config('simple-activity-log.registered', []) as $model) {
                $model::observe(config('simple-activity-log.logger'));
            }
        }
    }
}
