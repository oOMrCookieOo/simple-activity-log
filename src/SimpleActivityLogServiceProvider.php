<?php

namespace Mrcookie\SimpleActivityLog;

use Illuminate\Support\Facades\Log;
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
                    })->endWith(function (InstallCommand $installCommand) {
                        $installCommand->info('');
                        $installCommand->info('Please run "php artisan migrate" to create the activity log tables');
                        $installCommand->info('Don\'t forget to register your models in the config file!');
                    });
            });
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        try {
            $loggerClass = config('simple-activity-log-2.logger');

            if (!class_exists($loggerClass)) {
                throw new \Exception("Logger class {$loggerClass} does not exist.");
            }

            foreach (config('simple-activity-log-2.registered', []) as $modelClass) {
                if (class_exists($modelClass)) {
                    $modelClass::observe($loggerClass);
                } else {
                    Log::warning("SimpleActivityLog2: Model {$modelClass} does not exist.");
                }
            }
        } catch (\Exception $e) {
            Log::error("SimpleActivityLog2: {$e->getMessage()}");
        }
    }
}
