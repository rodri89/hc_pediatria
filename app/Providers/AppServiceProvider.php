<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Reintenta la conexión cuando el hosting la rechaza por exceso de pedidos simultáneos.
        // Laravel usa este conector para todas las conexiones con driver mysql.
        $this->app->bind('db.connector.mysql', \App\Database\ReintentoMySqlConnector::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
