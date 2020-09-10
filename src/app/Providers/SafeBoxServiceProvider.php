<?php


namespace App\Providers;

use App\Services\SafeBox;
use Illuminate\Support\ServiceProvider;

class SafeBoxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $development = env("ENV");
        if($development != "development") {
            $this->connectionSafeBox();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    protected function connectionSafeBox(){

        $variables = [
            'DB-SQLSRV-HOST',
            'DB-SQLSRV-USERNAME',
            'DB-SQLSRV-PASSWORD',
            'DB-SQLSRV-DATABASE'
        ];

        (new SafeBox(""))->getVariables($variables);

        config([
           "database.connections.sqlsrv.host" => env('DB_SQLSRV_HOST'),
           "database.connections.sqlsrv.database" => env('DB_SQLSRV_DATABASE'),
           "database.connections.sqlsrv.username" => env('DB_SQLSRV_USERNAME'),
           "database.connections.sqlsrv.password" => env('DB_SQLSRV_PASSWORD'),
        ]);
    }
}
