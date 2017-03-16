<?php

namespace MCMIS\Exporter;

use Illuminate\Contracts\Foundation\Application;

class Register
{

    /**
     * Bootstrap script
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app){
        $app->register(new \Maatwebsite\Excel\ExcelServiceProvider($app));
        $app->bind('MCMIS\Contracts\Exporter', 'MCMIS\Exporter\Container');
    }

}
