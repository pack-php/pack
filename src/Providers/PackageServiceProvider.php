<?php

namespace Pack\Providers;

use Illuminate\Support\ServiceProvider;
use Pack\Commands\InstallPackageCommand;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            InstallPackageCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
