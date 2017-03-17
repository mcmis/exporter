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
        $app->bind('MCMIS\Contracts\ExporterExtenders\ComplainExporterExtender', 'MCMIS\Exporter\Extenders\Complain');
        $app->bind('MCMIS\Contracts\ExporterExtenders\ReportExporterExtender', 'MCMIS\Exporter\Extenders\Report');
    }

}
